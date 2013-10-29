<?PHP 

namespace nrns;
use nrns;
	
	
	
	class responseProvider extends nrns\provider\provider {
		
		public $document;
		private $code = 200;
		private $contentType = 'text/html';
		private $body;
		
		public function __construct($nrns, $request) {
			$this->nrns = $nrns;
			$this->request = $request;
			
			$nrns->on("render", function(){
				
				
				header('Content-Type: '.$this->contentType, true, $this->code);
				if($this->body) {
					echo $this->body;
				}
			});
			
		}
		
		public function setContentType($type) {
			$this->contentType = $type;
		}
		
		public function setBody($body) {
			$this->body = $body;
		}
		
		public function setCode($code) {
			$this->code = $code;
		}
		
		
    
	}
	
	

?>