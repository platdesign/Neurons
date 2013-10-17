<?PHP namespace nrns;


class view {
	private $body = "";
	private $scope;
	private $subview;
	
	
	public function __construct($scope, $injection){
		$this->setScope($scope);
		$this->injection 	= $injection;
	}
	
	public function addSubview($view) {
		return $this->subview = $view;
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
		
		
		
		$this->setTemplateClosure(function($injection, $request, $route, $output, $client, $scope)use($url){
			
			return \nrns::loadTemplateContent($url, [
				"scope"		=>	$scope, 
				"route"		=>	$route, 
				"client"	=>	$client,
				"injector"	=>	$injection,
				"injection"	=>	$injection,
				"request"	=>	$request,
				"children"	=>	isset($this->subview)?$this->subview:"",
				"subview"	=>	isset($this->subview)?$this->subview:"",
				"output"	=>	$output,
			]);
				
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