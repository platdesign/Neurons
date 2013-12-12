<?PHP 
	namespace nrns\provider;
	use nrns;

	class ServiceProvider extends nrns\Provider {
		private $service;
		
		public function setService($service) {
			$this->service = $service;
		}
		
		public function getService() {
			return $this->service;
		}
		
	}
	
?>