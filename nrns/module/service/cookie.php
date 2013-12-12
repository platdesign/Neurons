<?PHP

	namespace nrns;
	use nrns;
	
	
	
	class cookie {
		
		public function set($key, $val, $lifetime=0, $path = null, $domain=null, $secure=false, $httponly=false) {
			setcookie($key, $val, time()+$lifetime, $path, $domain, $secure, $httponly);
			$_COOKIE[$key] = $val;
		}
		
		public function del($key) {
			setcookie($key, null, time()-1);
		}
		
		public function get($key) {
			
			if( isset($_COOKIE[$key]) ) {
				return $_COOKIE[$key];
			}
			
		}
		
	}

?>