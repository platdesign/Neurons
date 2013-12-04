<?PHP 

namespace nrns;
use nrns;
	
	
	
	class responseProvider extends nrns\provider\provider {
		
		public $document;
		private $code = 200;
		private $contentType = 'text/html';
		private $body;
		private $_headers = [];
		
		public function __construct($nrns, $request) {
			$this->nrns = $nrns;
			$this->request = $request;
			
			$nrns->on("render", function(){
				
				
				foreach($this->_headers as $val) {
					header($val, true, $this->code);
				}
				
				if($this->body) {
					echo $this->body;
				}
			});
			
		}
		
		public function setContentType($type) {
			$this->addHeader('Content-type: '.$type);
		}
		
		public function forceDownload($filename) {
			$this->addHeader('Content-disposition: attachment; filename='.$filename);
		}
		
		public function addHeader($content) {
			$this->_headers[] = $content;
		}
		
		public function setBody($body) {
			$this->body = $body;
		}
		
		public function setCode($code) {
			$this->code = $code;
		}
		
		
    
	}
	
	

?>