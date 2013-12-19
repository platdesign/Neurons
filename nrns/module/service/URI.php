<?PHP

	namespace nrns;
	use nrns;
	
	
	
	class URI {
		private $uri, $parsed;
		
		public function __construct($uri) {
			$this->uri = $uri;
			$this->parsed = self::parse($uri);
		}
		
		public static function parse($string) {
			return (object) parse_url($string);
		}
		
		public static function unparse($parsedObject) {
			return $parsedObject->scheme.'://'.$parsedObject->host.$parsedObject->path.'?'.$parsedObject->query;
		}
		
		
		public static function createFromCurrent() {
			$url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
			$url .= ( (int) $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
			$url .= $_SERVER["REQUEST_URI"];
			
			return new URI($url);
		}
		
		
		
		
		public function host() {
			return $this->parsed->host;
		}
		
		public function scheme() {
			return $this->parsed->scheme;
		}
		
		public function query() {
			return $this->parsed->query;
		}
		
		public function path() {
			return $this->parsed->path;
		}
		
		public function __tostring() {
			return self::unparse($this->parsed);
		}
	}

?>