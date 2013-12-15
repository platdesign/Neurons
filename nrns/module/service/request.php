<?PHP

	namespace nrns;
	use nrns;
	
	
	
	
	
	class request {
		
		
		use methodcache;
	
		/**
		 * Returns the request-route based on the route-path
		 *
		 * @example /route/to
		 * @return void
		 * @author Christian Blaschke
		 */
		public function cached_getRoute() {
			
			$scriptpath = $this->getScriptPath();
			$urlpath = $this->getPath();
			
			if(stripos($urlpath, $scriptpath) == 0) {
				$route = substr($urlpath, strlen($scriptpath));
			}
			if($route[0] != "/") { $route = "/".$route; }
			
			return $route;
		}



		/**
		 * Returns the request-path
		 *
		 * @example The path of http://www.example.com/path/A/?a=123 is /path
		 * @return void
		 * @author Christian Blaschke
		 */
		public function cached_getPath() {
			$url = $this->getUrl();
			$parsed = parse_url($url);
			
			return $parsed['path'];
		}



		/**
		 * Returns the base Url
		 *
		 * @example http://example.com
		 * @return string
		 * @author Christian Blaschke
		 */
		public function cached_getBase() {
			
			return $this->getProtocol()."://".$_SERVER['SERVER_NAME'].$this->getScriptPath();
			
			
			$fullUrl 	= $this->getUrl();
			$scriptpath = $this->getScriptPath();
			return rtrim(substr($fullUrl, 0, strrpos($fullUrl, $scriptpath)).$scriptpath, "/");
			
		}
	
	
		/**
		 * Returns the scriptpath
		 *
		 * @return string
		 * @author Christian Blaschke
		 */
		public function cached_getScriptPath() {
			return dirname($_SERVER['SCRIPT_NAME']);
		}
	
		/**
		 * Returns the request-protocol
		 *
		 * @example http | https
		 * @return string
		 * @author Christian Blaschke
		 */
		public function cached_getProtocol() {
			return (!empty($_SERVER['HTTPS'])) ? "https" : "http";
		}
	
	
		
		/**
		 * Returns the request-method
		 *
		 * @example GET | POST | PUT | DELETE
		 * @return string
		 * @author Christian Blaschke
		 */
		public function cached_getMethod(){
			return strtoupper($_SERVER['REQUEST_METHOD']);
		}
		
		
		
		/**
		 * Returns the full request-url
		 *
		 * @example http://sub.example.com/dir/file.php?a=123
		 * @return void
		 * @author Christian Blaschke
		 */
		public function cached_getUrl() {
			return $this->getProtocol()."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
	
	
	
		/**
		 * Returns the request-body
		 *
		 * @return string
		 * @author Christian Blaschke
		 */
		public function cached_getBody() {
			return file_get_contents('php://input');
		}



		/**
		 * Redirects to an url
		 *
		 * @param string $url 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function redirect($url) {
			if($url != $this->getUrl()) {
				header("Location: ".$url);
				die();
			}
		}
	
	
	
		/**
		 * Redirects to a route
		 *
		 * @param string $route 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function redirectRoute($route) {
			$this->redirect($this->getBase().$route);
		}
	}
	


?>