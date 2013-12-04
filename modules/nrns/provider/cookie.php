<?PHP

	namespace nrns;
	use nrns;
	
	
	
	
	
	class cookie {
		
		public function set($name, $content, $lifetime=0, $path="/") {
			
			if($lifetime!==0) {
				$lifetime += time();
			} 
			
			setcookie($name, $content, $lifetime, $path);
		}
		
		public function get($name) {
			if(isset($_COOKIE[$name])) {
				return $_COOKIE[$name];
			}
		}
		
		public function destroy($name) {
			$this->set($name, null, -1);
		}
		
	}


?>