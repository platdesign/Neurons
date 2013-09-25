<?PHP
class route {
	private $method, $__then, $__subroutes = [];
	public $__route, $params;
	public $view;
	
	private $routeParamRegEx = "#:([\w]+)\+?#";
	
	public function __construct($app, $request, $output, $injection, $routeProvider) {

		$this->app 				= $app;
		$this->injection 		= $injection;
		$this->routeProvider 	= $routeProvider;
		$this->request			= $request;
		$this->output			= $output;
		
		$this->params	= (object) [];
		
		$this->scope = $this->app->createScope();
		
		$this->view = $this->injection->invokeClass("nrns\\view", $this->scope);
	}

	public function setMethod($method) {
		$this->method = $method;
	}
	
	public function setRoute($route) {
		$this->__route 	= $route;
		$this->__routePart	= substr($this->__route, strripos($this->__route, "/"));
	}

	public function call() {
		$this->walkControllerChain();
		$this->walkViewChain();
		
		$this->output->setBody((string) $this->getTopRoute()->view);
	}

	

	public function walkControllerChain() {
		
		if( isset($this->_parent) ) {
			$this->_parent->walkControllerChain();
			\nrns::extendObj($this->scope, $this->_parent->scope);
		}
		
		$this->injection->invokeClosure($this->controller, $this->scope);
		
	}
	
	public function walkViewChain() {
		
		if( isset($this->_parent) ) {
			$this->_parent->view->addSubview(str_replace(array("/", ":"), "",$this->__routePart), $this->view);
			$this->_parent->walkViewChain();
		}
		
	}

	public function getTopRoute() {
		if( isset($this->_parent) ) {
			return $this->_parent->getTopRoute();
		} else {
			return $this;
		}
	}
	
	public function setThen($then) {
		$this->__then = $then;
		
		$this->analyseOptions();
		
		
		/*if(is_array($then)) {
		
			// OPTION: subroutes
				if( isset($then["subroutes"]) ) {
					$subroutes = $then["subroutes"];
					if(is_array($subroutes)) {
						$baseroute = ($this->__route == "/")?"":$this->__route;
						foreach($subroutes as $route => $then) {
						
							$subroute = $this->routeProvider->addRoute("ALL", $baseroute.$route, $then);
							$subroute->__parentRoute = $this;
							
						}
					}
				}

		}
		*/
	}
	
	public function callController() {
		
		$then = $this->__then;
		$injection = $this->app->injectionProvider;
		
		if(isset($this->__parentRoute)) {
			
			$this->__parentRoute->callController();
			$this->scope = $this->__parentRoute->scope;
			
		}
		
		
		// OPTION: controllerUrl

			// If option controllerUrl is set
			if( isset($then['controllerUrl']) ) {
				
				$ctrlUrl = $then['controllerUrl'];
				
				if( is_string($ctrlUrl) AND file_exists($ctrlUrl) ) {
					$then['controller'] = require($ctrlUrl);
				}
				
				
			}
		
		// OPTION: controller
			
			// If option controller is set
			if( isset($then['controller']) ) {
				
				
				// If controller is a string, call the controller from the app-ctrl-stack
				if( is_string($then['controller']) ) {
					$app->controller( $then['controller'], $this->scope);
				}
				
				
				// If controller is a closure, invoke the auto-injected closure
				if( is_callable($then['controller']) ) {
					$injection->invokeClosure( $then['controller'], $this->scope);
				}
				
			}
		
			
	}
	
	public function callView() {
		
		$then = $this->__then;
		
		// OPTION: templateUrl
			if( isset($then['templateUrl']) ) {
				$this->view->setTemplateUrl($then['templateUrl']);
			}
		
		
		
		
		// OPTION: view
		
			if( isset($then['view']) ) {
				
				if( is_string($then['view']) ) {
					
				} elseif( is_callable( $then['view'] )) {
					$this->view->setTemplateClosure( $then['view'] );
				}
				
			}
		
		/*
		
		// OPTION: templateUrl
			if( isset($then['templateUrl']) ) {
				$this->view->setTemplateUrl($then['templateUrl']);
			}
		
		
		// OPTION: httpCode
		
			if( isset($then['httpCode']) ) {
				$injection->invokeClosure(function($output)use($then){
					$output->setCode($then['httpCode']);
				});
			}
		

		*/
		
		
			
		if( isset($this->__parentRoute) ) {
			$this->__parentRoute->view->subview = $this->view;
			$this->__parentRoute->callView();
		} else {
			$this->view->setScope($this->scope);
			$this->routeProvider->output->setBody((string)$this->view);
		}
		
	}
	
	
	
	private function analyseOptions() {
		
		$this->controller = function(){};
		
		if(is_array($this->__then)) {
			foreach($this->__then as $key => $options) {
				switch($key) {
					
					

					/* ROUTE OPTIONS */
					
						case "templateUrl":
							$this->view->setTemplateUrl($options);
						break;
				
						case "view":
							$this->view->setTemplateClosure($options);
						break;
				
						case "controller":
							if(is_callable($options)) {
								$this->controller = $options;
							}
						break;
				
						case "controllerUrl":
							if(file_exists($options)) {
								$this->controller = require_once $options;
							}
						break;
				
						case "extend":
							
							foreach($options as $route => $then) {
								$this->addSubroute($this->method, $route, $then);
							}
							
						break;
					
						case "redirect":
							$this->controller = function($request)use($options) { $request->redirectRoute(str_replace("//", "/", $options)); };
						break;
					
						/* END ROUTE OPTIONS */
					


				}
			}
		} elseif( is_callable($this->__then) ) {
			$this->controller = $this->__then;
		}
		
		
	}
	
	
	
	public function addSubroute($method, $route, $then) {
		$subroute = $this->routeProvider->addRoute($method, $this->__route.$route, $then);
		$subroute->_parent = $this;
	}
	
	
	
	
	
	public function notfound() {
		$this->routeProvider->getNotFoundRouteObject()->call();
	}
	
	public function setParams($vals) {
		$keys = $this->getKeys();
		$this->params = (object) array_combine($keys, $vals);
	}
	
	public function getKeys() {
		preg_match_all($this->routeParamRegEx, $this->__route, $matches);
		$result = [];
		foreach($matches[0] as $key) {
			$result[] = substr($key, 1);
		}
		return $result;
	}
	
	public function matchesWith($route) {
		$pattern = preg_replace($this->routeParamRegEx, "([\w]+)", $this->__route);
		
        if (preg_match('#^/?' . $pattern . '/?$#', $route, $matches)) {
			unset($matches[0]);
			$this->setParams($matches);
			$this->route = $route;
            return true;
        }
		
	}
	
}

?>