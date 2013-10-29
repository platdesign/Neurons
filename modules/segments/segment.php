<?PHP 
	
	namespace segments;
	use nrns;
	

	class segment extends nrns\segment {
	
		public $templateUrl, $controllerUrl, $controller, $code=200;
	
	
		public function segment($name, $options=[]) {
			
			$segment = $this->createChild($name);
			$segment->setOptions($options);
			return $segment;
			
		}
		
		public function setOptions($opts=[]) {
			
			if( isset($opts['templateUrl']) ) {
				$this->templateUrl = $opts['templateUrl'];
			}
			
			if( isset($opts['controllerUrl']) ) {
				$this->controllerUrl = $opts['controllerUrl'];
			}
			
			if( isset($opts['controller']) ) {
				$this->controller = $opts['controller'];
			}
			
			if( isset($opts['code']) ) {
				$this->code = $opts['code'];
			}
			
		}
		
		
		
		
	}
	
	

?>