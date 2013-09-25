<?PHP

namespace nrns\provider {
	
	class jsonApiProvider extends provider {
		
		private $version = "0.1";
		private $ctrlSuffix = "jsonApiProviderCtrl__";
		private $apiName = "jsonAPI";
		

		
		public function __construct($app, $routeProvider, $outputProvider) {

			$this->app 				= $app;
			$this->routeProvider 	= $routeProvider;
			$this->outputProvider 	= $outputProvider;
			
			$outputProvider->setType("json");
			
			//$this->jsonViewName = $this->ctrlSuffix."jsonView";
			
		}
		
		public function __init() {
			$this->registerHome();
			$this->registerNotFound();
		}
		
		public function setApiName($name) {
			$this->apiName = $name;
		}
		
		public function setApiAuthor($name) {
			$this->apiAuthor = $name;
		}
		
		private function registerNotFound() {
			
			$this->routeProvider->otherwise($this->getContentClosure(function(){
				throw new \Exception("not found", 404);
			}));
			
		}
		
		private function registerHome() {
			
			$this->when("/", function($jsonApi) {
				
				$result = new \stdClass;
				
				$result->version 	= $jsonApi->version;
				$result->name 		= $jsonApi->apiName;
				$result->author 	= $jsonApi->apiAuthor;
				
				return $result;
			});
			
		}
		
		public function addRoute($method, $route, $closure) {
			$this->routeProvider->addRoute($method, $route, $this->getContentClosure($closure));
		}
		
		public function when($route, $closure) {
			$this->addRoute("ALL", $route, $closure);
		}
		public function get($route, $closure) {
			$this->addRoute("GET", $route, $closure);
		}
		public function post($route, $closure) {
			$this->addRoute("POST", $route, $closure);
		}
		public function put($route, $closure) {
			$this->addRoute("PUT", $route, $closure);
		}
		public function delete($route, $closure) {
			$this->addRoute("DELETE", $route, $closure);
		}
		
		private function getContentClosure($closure) {
			return function($injection, $jsonApi, $output)use($closure) {
				
				try {
					$result = $injection->invokeClosure($closure);
					$output->setBody($result);
				}catch(\Exception $e){
					$jsonApi->outputError($e->getMessage(), $e->getCode());
				}
				
			};
		}
		
		public function addResource($route, $dao) {
			
			
			$this->get($route, function()use($dao){
				return $dao->selectObjects();
			});
			
			$this->get($route."/:id", function($route)use($dao){
				return $dao->selectObject($route->params->id);
			});

			// TODO: FILL IT WITH CONTENT!
			$this->post($route, function($route)use($dao){
				
			});
			
			$this->put($route."/:id", function($route)use($dao){
				
			});
			
			$this->delete($route."/:id", function($route)use($dao){
				
			});
			
			
		}
		
		public function outputError($message, $code=200) {
			if($code >= 200) {
				$this->outputProvider->setCode($code);
			}
			$this->outputProvider->setBody((object)["error"=>["message"=>$message, "code"=>$this->outputProvider->getCode()]]);
			
		}
		
		
		public function __invoke() {
			return $this;
		}
    
	}
	
}	

?>