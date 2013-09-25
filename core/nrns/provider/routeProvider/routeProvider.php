<?PHP namespace nrns\provider;
	use nrns;
	require "route.php";

	class routeProvider extends provider {
		private $routes = ["ALL"=>[], "GET"=>[], "POST"=>[], "PUT"=>[], "DELETE"=>[]];
		public $current;
		private $notFoundRoute = "__routeprovider__routenotfound__";
		
		
		
		
		
		public function __construct($request, $app, $output, $injection) {
			$this->injection = $injection;
			$this->request 	= $request;
			$this->app 		= $app;
			$this->output 	= $output;
			
			$this->checkForHtaccess();
			
			
			$app->addListener("start", function(){
				$routeObject = $this->getRouteObject();
				if( method_exists($routeObject, "call") ) {
					$this->__activeRoute = $routeObject;
					$routeObject->call($this->app);
				}
			});
			
		}
		
		public function __init() {
			$this->addRoute("ALL", $this->notFoundRoute, function(){});
		}
		
		public function otherwise($then) {
			$routeObject = $this->getNotFoundRouteObject();
			$routeObject->setThen($then);
			return $this;
		}
		
		public function getNotFoundRouteObject() {
			return $this->routes["ALL"][$this->notFoundRoute];
		}
		
		public function when($route, $then) {
			$this->addRoute("ALL", $route, $then);
			return $this;
		}
		
		public function get($route, $then) {
			$this->addRoute("GET", $route, $then);
			return $this;
		}
		
		public function post($route, $then) {
			$this->addRoute("POST", $route, $then);
			return $this;
		}
		
		public function put($route, $then) {
			$this->addRoute("PUT", $route, $then);
			return $this;
		}
		
		public function delete($route, $then) {
			$this->addRoute("DELETE", $route, $then);
			return $this;
		}
		
		public function addRoute($method, $route, $then) {
			$route = str_replace("//", "/", $route);

			$routeObject = $this->injection->invokeClass("route");
			$routeObject->setMethod($method);
			$routeObject->setRoute($route);
			$routeObject->setThen($then);
			
			return $this->routes[$method][$route] = $routeObject;
			
		}



		
		private function getRouteObject() {
			$method = $this->request->method;
			$route 	= $this->request->route;
			
			if( $all = $this->getRouteObjectByMethodAndRoute("ALL", $route) ) {
				return $all;
			} elseif($else = $this->getRouteObjectByMethodAndRoute($method, $route)) {
				return $else;
			}
			
			return $this->getNotFoundRouteObject();
		}
		
		private function getRouteObjectByMethodAndRoute($method, $activeRoute) {
			
			$routes = $this->routes[$method];

			if( is_array($routes) AND count($routes) > 0 ) {
				if( isset($routes[ $activeRoute ]) ) {
					return $routes[ $activeRoute ];
				} else {
					foreach ($routes as $route => $object) {
						if($object->matchesWith($activeRoute)) {
							return $object;
						}
					}
				}
			}
			
		}


		
		
		
    	public function __invoke() {
    		return $this->__activeRoute;
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