<?PHP

namespace nrns\module\router\classes;
use nrns;

class route {
	
	
	
	private $method, $options = [], $controller;
	public $route, $params = [], $parenRoute, $childRoute;
	
	private $routeParamRegEx = "#:([\w]+)\+?#";
	
	public function __construct($app, $request, $output, $injection, $routeProvider, $scope) {

		$this->app 				= $app;
		$this->injection 		= $injection;
		$this->routeProvider 	= $routeProvider;
		$this->request			= $request;
		$this->output			= $output;
		$this->scope 			= $scope;
		
		$this->params	= (object) [];
		
		$this->controller = $this->injection->invokeClass("nrns\\controller", $this->scope);
	}


	public function setMethod($method) {
		$this->method = $method;
	}
	
	public function setRoute($route) {
		$this->route = $route;
	}
	
	public function setOptions($options) {
		$this->options = $options;
	}
	
	
	
	
	public function call() {
		$this->options2controller();
		return $this->controller->call();
	}
	
	private function options2controller() {
		
		if(is_array($this->options)) {
			
			$view = $this->injection->invokeClass("nrns\\view", $this->scope);
			$ctrl = $this->injection->invokeClass("nrns\\controller", $this->scope);
			
			foreach($this->options as $key => $options) {
				switch($key) {
					
						case "templateUrl":
							$view->setTemplateUrl($options);
						break;
				
						case "view":
							$view->setTemplateClosure($options);
						break;
				
						case "controller":
							if(is_callable($options)) {
								$ctrl->setClosure($options);
							}
						break;
				
						case "controllerUrl":
							if(file_exists($options)) {
								$ctrl->setFile($options);
							}
						break;
					
						case "redirect":
							$ctrl->setClosure(function($request)use($options){
								$request->redirectRoute(str_replace("//", "/", $options));
							});
						break;
					
				}
			}

			$this->controller->setClosure(function($output)use($view, $ctrl){
				$ctrl->call();
				$output->setBody( $view );
			});

		} elseif( is_callable($this->options) ) {
			$this->controller->setClosure($this->options);
		}
		
		
	}

	
	
	
	private function setParams($vals) {
		$keys = $this->getKeys();
		$this->params = (object) array_combine($keys, $vals);
	}
	
	private function getKeys() {
		preg_match_all($this->routeParamRegEx, $this->route, $matches);
		$result = [];
		foreach($matches[0] as $key) {
			$result[] = substr($key, 1);
		}
		return $result;
	}
	
	public function matchesWith($route) {
		$pattern = preg_replace($this->routeParamRegEx, "([\w]+)", $this->route);
		
        if (preg_match('#^/?' . $pattern . '/?$#', $route, $matches)) {
			unset($matches[0]);
			
			$this->setParams($matches);
			$this->setRoute($route);
            return true;
        }
		
	}
	
}

?>