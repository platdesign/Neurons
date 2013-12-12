<?PHP

	namespace nrns;
	use nrns;
	
	
	
	
	
	class response {
		private $body, $contentType='text/html', $http_response_code=200;
		
		public function __construct($nrns) {
			$nrns->on('render', function(){
				$this->sendHeader('Content-type: '.$this->contentType);
				echo $this->body;
			});
		}
		
		public function setBody($body) {
			$this->body = $body;
		}
		
		public function setCode($code) {
			$this->http_response_code = $code;
		}
		
		public function ContentType($type) {
			
			switch($type) {
				case 'JSON':
					$this->contentType = 'application/json';
				break;
				case 'HTML':
					$this->contentType = 'text/html';
				break;
				default:
					$this->contentType = $type;
				break;
			}
		}


		public function sendHeader($content) {
			header($content, false, $this->http_response_code);
		}

	}
	


?>