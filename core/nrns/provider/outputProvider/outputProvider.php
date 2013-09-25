<?PHP namespace nrns\provider {

	require "I_outputDocument.php";
	require "outputDocument.php";
	require "htmlDocument.php";
	
	
	class outputProvider extends provider {
		
		private $title;
		
		private $type = "HTML";
		private $document;
		
		private $code = 200;
		
		public function __construct($request, $injection, $app) {
			$this->injection 	= $injection;
			$this->request 		= $request;
			
			$this->setType($this->type);
			
			$app->addListener("close", function(){
				$this->render();
			});
			
		}
		
		public function setType($type) {
			$this->type = strtoupper($type);
			
			switch($this->type) {
				case "JSON":
					$this->document = $this->injection->invokeClass("jsonDocument");
				break;
				case "HTML":
					$this->document = $this->injection->invokeClass("htmlDocument");
				break;
			}
		}

		public function setCode($code) {
			$this->code = $code;
		}
		public function getCode() {
			return $this->code;
		}
		
		public function render() {
			echo $this->document->render();
		}
		
		
		public function __invoke() {
			return $this->document;
		}
    
	}
	
}	

?>