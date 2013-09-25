<?PHP
namespace nrns\provider {
	
	class provider {

		private $____serviceExtender = [];

		public function __construct() {
			
		}
		
		public function __invoke(){
			return $this;
		}
		
		
		public final function extendService($name, $object) {
			$this->____serviceExtender[$name] = $object;
		}
		
		public final function extend($name, $object) {
			$this->{$name} = $object;
		}
		
		public final function getService() {
			$service = $this->__invoke();
			
			if($this->____serviceExtender) {
				foreach($this->____serviceExtender as $key => $extender) {
					$service->{$key} = $extender;
				}
			}
			
			return $service;
		}
	}
	
}
?>