<?PHP 

namespace nrns;
use nrns;
	
	require "outputDocument.php";
	require "htmlDocument.php";

	
	class outputProvider extends nrns\provider\provider {
		
		private $title;
		
		private $type = "HTML";
		private $document;
		
		private $code = 200;
		
		public function __construct($request, $injection, $nrns) {
			$this->injection 	= $injection;
			$this->request 		= $request;
			
			$this->setType($this->type);
			
			$nrns->on("render", function(){
				$this->render();
			});
			
		}
		
		public function setType($type) {
			$this->type = strtoupper($type);
			
			
			switch($this->type) {
				case "HTML":
					$this->document = $this->injection->invokeClass("nrns\\provider\\outputProvider\\htmlDocument");
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
	
	

?>