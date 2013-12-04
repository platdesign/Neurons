<?PHP 
	
	namespace express;
	use nrns;
	
	class expressException extends \Exception {
	}
	
	class expressProvider extends nrns\provider\provider {
		
		// Load routeSetter-methods (when, get, put, post, delete)
		use \router\routeSetter;
		
		private $__authenticationChecker;
		private $resourcesDir;
		
		public function __construct($injection, $routeProvider, $response, $fs, $nrns) {
			$this->routeProvider 	= $routeProvider;
			$this->response 		= $response;
			$this->injection 		= $injection;
			$this->fs 				= $fs;
			$this->nrns				= $nrns;
		}
		
		public function addRoute($method, $route, $then) {
			
			$route = $this->routeProvider->addRoute($method, $route, function()use($then){
				$this->resolve($then);
			});
			$route->dataClosure = $then;
			return $route;
		}
		
		public function otherwise($then) {
			return $this->routeProvider->otherwise(function()use($then){
				$this->resolve($then);
			});
		}
		
		public function resolve($closure) {
			$data = new \stdClass;
			
			try {
				
				$data = $this->injection->invoke($closure);
				
			}catch(\PDOException $e) {
				
				$data->error = (object) [
					"message"=>"SQL ERROR",
					"code"	=>	500,
					"description" => "Ein SQL-Fehler ist aufgetreten.",
					"SQLSTATE"=> $e->getCode(),
					"details" => $e->getMessage()
				];
				
				$this->response->setCode(500);
				
			}catch(expressException $e){
				
				$data->error = $this->createErrorObject($e);
				
			}catch(\Exception $e){
				
				try {
					$this->handleException($e);
				}catch(expressException $e) {
					$data->error = $this->createErrorObject($e);
				}
				
				
				
				/*
				if($this->nrns->devMode()) {
					
					$data->error = (object) [
						"message"	=> $e->getMessage(),
						"code"		=>	500
					];
					
				} else {
					$data->error = (object) [
						"message"=>"Fatal Error",
						"code"	=>	500,
						"description" => "Ein unbekannter Serverfehler ist aufgetreten.",
						"details" => "Kontaktieren sie den Entwickler."
					];
				}
				*/
				
				
				
			}
		
		
			$this->response->setContentType("application/json");
			$this->response->setBody( json_encode($data, JSON_NUMERIC_CHECK) );
		
		
		}
		
		private function createErrorObject($e) {
			$error = json_decode($e->getMessage());
			$error->code = $e->getCode();
			
			$this->response->setCode($error->code);
			
			return $error;
		}
		
		public function addExceptionHandler($classname, $handler) {
			$this->exceptionHandlers[$classname] = $handler;
		}
		
		private function handleException($e) {
			$classname = get_class($e);
			
			if( $handler = $this->exceptionHandlers[$classname] ) {
				call_user_func_array($handler->bindTo($this), [$e]);
			}
		}
		
		public function collection($data) {
			$collection = new \stdClass;
			$collection->items = is_array($data) ? $data : [];
			
			$collection->length = count($collection->items);
			return $collection;
		}
	
		public function model($data) {
			return $data;
		}
	
		public function error($code, $message, $desc="", $details="") {
			
			if(is_array($message) || is_object($message)) {
				$obj = $message;
			} else {
				$obj = (object) [
					"message"		=>	$message,
					"description"	=>	$desc,
					"details"		=>	$details
				];
			}
			
			
			
			throw new expressException(json_encode($obj), $code);
		}
	
		public function checkAuthentication() {
			if(is_callable($this->__authenticationChecker)) {
				if(!$this->injection->invoke($this->_authenticationChecker)) {
				
					$this->error(401, "Unauthorized", "User needs to be logged in.");
				
				}
			}
		}
		
		public function setAuthenticationChecker($closure) {
			$this->_authenticationChecker = $closure;
		}
		
		public function resource($name) {
			
			$this->requireResourceFile($name);
			
			$this
				->get(		"/$name", 		"express\\resources\\$name::getCollection")
				->get(		"/$name/:id", 	"express\\resources\\$name::getModel")
				->post(		"/$name", 		"express\\resources\\$name::createModel")
				->put(		"/$name/:id", 	"express\\resources\\$name::updateModel")
				->delete(	"/$name/:id",	"express\\resources\\$name::deleteModel");
		}
		
		public function setResourcesDir($dir) {
			
			$this->resourcesDir = $this->fs->find($dir);

			if(!$this->resourcesDir) {
				throw new \Exception("Resourcedir not found!");
			}
		}
		
		public function requireResourceFile($resourcename) {
			
			if($this->resourcesDir) {
				if($file = $this->resourcesDir->find($resourcename.".php")) {
					$file->import();
				}
			}
			
		}
		
		public function getDataOfRoute($route, $method="GET") {
			$routeObject = $this->routeProvider->findRouteObject($method, $route);
			
			$activeRoute = $this->routeProvider->getActiveRoute();
			
			// TODO: DO NOT MERGE TO $activeRoute->params!
			// Change "injection->invoke"-method that it is possible to overwrite $route in $routeObject->dataClosure
			$activeRoute->params = (object) array_merge((array)$activeRoute->params, (array)$routeObject->params);
			
			return $this->injection->invoke($routeObject->dataClosure);
		}
		
	}
	
	

?>