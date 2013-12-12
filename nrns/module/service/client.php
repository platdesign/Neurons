<?PHP

	namespace nrns;
	use nrns;
	
	
	
	class client {
		
		use methodcache;
		
		public function cached_getLanguage() {
			return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}
		
	}

?>