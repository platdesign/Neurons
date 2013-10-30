<?PHP

namespace router;
use nrns;

class route {
	
	use nrns\events;
	
	private $method, $options = [], $controller;
	public $_route, $route, $params = [], $parenRoute, $childRoute;
	
	private $routeParamRegEx = "#:([\w.-]+)\+?#";
	
	public function __construct($nrns, $request, $injection, $routeProvider, $scope) {

		$this->injection 		= $injection;
		$this->routeProvider 	= $routeProvider;
		$this->request			= $request;
		$this->scope 			= $scope;
		
		$this->params	= (object) [];
		
		$this->controller = $this->injection->invokeClass("nrns\\controller", $this->scope);
	}


	public function setMethod($method) {
		$this->method = $method;
	}
	
	public function setRoute($route) {
		$this->_route = $route;
	}
	
	public function setOptions($options) {
		$this->options = $options;
	}
	
	
	
	
	public function call() {
		$this->trigger('call');
		if( is_callable($this->options) ) {
			$this->controller->setClosure($this->options);
		}
		return $this->controller->call();
	}
	


	
	
	
	
	
	
	
	
	private function setParams($vals) {
		$keys = $this->getKeys();
		$this->params = (object) array_combine($keys, $vals);
	}
	
	private function getKeys() {
		preg_match_all($this->routeParamRegEx, $this->_route, $matches);
		$result = [];
		foreach($matches[0] as $key) {
			$result[] = substr($key, 1);
		}
		return $result;
	}
	
	public function matchesWith($route) {
		$pattern = preg_replace($this->routeParamRegEx, "([\w.-]+)", $this->_route);
		
        if (preg_match('#^/?' . $pattern . '/?$#', $route, $matches)) {
			unset($matches[0]);
			
			$this->setParams($matches);
			$this->route = $route;
			
			
            return true;
        }
		
	}
	
	
	public function __tostring() {
		return $this->route;
	}
}

?>