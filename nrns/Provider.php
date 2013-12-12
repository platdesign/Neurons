<?PHP 
	namespace nrns;
	use nrns;

	class Provider {
		use events;
		
		public function getService() {
			return $this;
		}
		
	}
	
?>