<?PHP 
	
	namespace nrns\module\router\provider;
	use nrns;
	

	class routeProvider extends nrns\provider\provider {
		
		// Load routeSetter-methods (when, get, put, post, delete)
		use nrns\module\router\traits\routeSetter;
		
		
		private $routes = ["ALL"=>[], "GET"=>[], "POST"=>[], "PUT"=>[], "DELETE"=>[]];
			
		private $activeRoute;
		private $otherwiseRoute;
		
		private $routeClassname = "nrns\\module\\router\\classes\\route";
		
		
		
		public function __construct($request, $app, $injection) {
			$this->injection 	= $injection;
			$this->request 		= $request;
			$this->app 			= $app;
			
			$this->checkForHtaccess();
		}
		
		
		public function init() {
			// Create empty route for otherwise and set an emtpy controller
			$this->otherwiseRoute = $this->injection->invokeClass($this->routeClassname);
				
		
			// Add event to the app which executes the active route on app-start
			$this->app->on("start", function(){
				$this->activeRoute = $this->findRoute();
				
				if( method_exists($this->activeRoute, "call") ) {
					$this->activeRoute->call($this->app);
				}
			});
		}
		
		
		
		
		public function otherwise($then) {
			$this->otherwiseRoute->setOptions($then);
			return $this;
		}
		
		
		
		
		public function addRoute($method, $route, $then, $scope=null) {
			$route = str_replace("//", "/", $route);

			$routeObject = $this->injection->invokeClass($this->routeClassname, $scope);
			$routeObject->setMethod($method);
			$routeObject->setRoute($route);
			$routeObject->setOptions($then);
			
			return $this->routes[$method][$route] = $routeObject;
		}




		
		private function findRoute() {
			$method = $this->request->method;
			$route 	= $this->request->route;
			
			if( $all = $this->findRouteObject("ALL", $route) ) {
				return $all;
			} elseif($else = $this->findRouteObject($method, $route)) {
				return $else;
			}
			
			return $this->otherwiseRoute;
		}
		
		
		private function findRouteObject($method, $route) {
			
			$routes = $this->routes[$method];

			if( count($routes) > 0 ) {
				
				// look up in static routes
				if( isset($routes[ $route ]) ) {
					return $routes[ $route ];
				} else {
				
				// look up in dynamic routes
					foreach ($routes as $object) {
						if( $object->matchesWith($route) ) {
							return $object;
						}
					}
				}
				
			}
			
		}


		
		
		
    	public function __invoke() {
    		return $this->activeRoute;
    	}
	
	
	
	
	
	
	
	
	
	
	
		public function getAllRoutes($method) {
			$routes = $this->routes[$method];
			$result = [];
			foreach($routes as $route=>$object) {
				$result[] = $route;
			}
			return $result;
		}
	
	
	
	
		private function checkForHtaccess() {
			$htaccessFile = $this->app->__rootpath."/.htaccess";
			
			
			if(!file_exists($this->app->__rootpath."/.htaccess")) {
				
				$htaccessContent = str_replace("\t", "", 'RewriteEngine On

					RewriteBase '.dirname($_SERVER['SCRIPT_NAME']).'

					# Remove double slashes in whole URL
					RewriteCond %{REQUEST_URI} ^(.*)//(.*)$
					RewriteRule . %1/%2 [R=301,L]

					# Send each Request to index.php
					RewriteCond %{REQUEST_FILENAME} !-f
					RewriteCond %{REQUEST_FILENAME} !-d
					RewriteRule ^ index.php [QSA,L]
				');
				
				
				
				// try to create .htaccess-File
				
				if( !file_put_contents($htaccessFile, $htaccessContent) ) {
					\nrns::displaySysError("Create .htaccess-File at <br><pre>".$htaccessFile."</pre> with following Content<hr><pre>".$htaccessContent."</pre>");
				}
				
			}
			
		}
		
		
	
	}
	
	

?>