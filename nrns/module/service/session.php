<?PHP

	namespace nrns;
	use nrns;
	
	session_start();
	
	class session {
		
		public function set($key, $val) {
			$_SESSION[$key] = $val;
		}
		
		public function del($key) {
			if( isset($_SESSION[$key]) ) {
				unset($_SESSION[$key]);
			}
		}
		
		public function get($key) {
			
			if( isset($_SESSION[$key]) ) {
				return $_SESSION[$key];
			}
			
		}
		
	}

?>