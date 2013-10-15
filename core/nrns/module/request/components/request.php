<?PHP
namespace nrns\module\request\components;
	
	
	
	
	
	
	class request {
		public function __construct() {
			$this->server 		= $_SERVER;
		
			$this->url 			= $this->getUrl();
			$this->parsedUrl 	= parse_url($this->url);
			$this->method 		= $this->server['REQUEST_METHOD'];
			$this->route 		= $this->getRoute();
			$this->base 		= $this->getBase();
			
		
		}

		private function removeTrailingSlash($uri) {
			return rtrim($uri, "/");
		}

		public function getBody() {
			return file_get_contents('php://input');
		}
	
		private function getUrl() {
			$scheme = (!empty($this->server['HTTPS'])) ? "https" : "http";
		
			$url = $scheme."://".$this->server['SERVER_NAME'].$this->server['REQUEST_URI'];
			return $url;
		}
	
		private function getRoute() {
			
			
			
			$scriptpath = dirname($this->server['SCRIPT_NAME']);
			$urlpath = $this->parsedUrl['path'];
			

			
			if(stripos($urlpath, $scriptpath) == 0) {
				$length = strlen($scriptpath);
				$route = substr($urlpath, $length);
			}
			if(substr($route, 0, 1)!="/") {$route = "/".$route;}
			
			return $route;
		}

		private function getBase() {
			return rtrim($this->parsedUrl['scheme']."://".$this->parsedUrl['host'].dirname($this->server['SCRIPT_NAME']), "/");
		}
	
		public function redirect($url) {
			if($url != $this->url) {
				
				header("Location: ".$url);
				die();
			}
		}
	
		public function redirectRoute($route) {
			$this->redirect($this->parsedUrl['scheme']."://".dirname($this->server['SERVER_NAME'].$this->server['SCRIPT_NAME']) . $route);
		}
	}
	


?>