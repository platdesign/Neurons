<?PHP 

	namespace nrns;


	class view {
		private $body = "";
		private $scope;
		private $subview;
		private $globals=[];
	
		public function __construct($scope, $injection){
			$this->setScope($scope);
			$this->injection 	= $injection;
			$this->subview = new \stdClass;
		}
	

		public function setGlobal($key, $val) {
			$this->globals[$key] = $val;
		}




		public function addSubview($name, $view) {
			return $this->subview->{$name} = $view;
		}
	
		public function createSubview($name) {
			return $this->subview->{$name} = $this->injection->invokeClass(get_class($this), $this->scope);
		}
	
		public function setScope($scope) {
			$this->scope = $scope;
		
		}

	
	
		public function setBody($body) {
			$this->setTemplateClosure(function()use($body){
				return $body;
			});
		}
	
		public function setTemplateUrl($url) {
			if(substr($url, 0, 2) == "./") { $url = \nrns::$rootpath."/".$url; }
		
			$this->setTemplateClosure(function($injection, $request, $route, $client, $scope)use($url){
			
				$globals = array_merge([
					"scope"		=>	$scope
				], $this->globals);
			
				return \nrns::loadTemplateContent($url, $globals);
				
			});
		
		}
	
		public function setTemplateClosure($closure) {
			$this->templateClosure = $closure->bindTo($this);
		}
	
	
	
		public function render() {
			try {
		
				if( isset($this->templateClosure) ) {
					$this->body = $this->injection->invokeClosure($this->templateClosure, $this->scope);
				}
		
				return (string) $this->body;
			}catch(Exception $e){
			
			}
		
		}
	
		public function __tostring() {
			try {
				return $this->render();
			}catch(Exception $e) {
				return $e->getMessage();
			}
		
		}	
	}
	
	
?>