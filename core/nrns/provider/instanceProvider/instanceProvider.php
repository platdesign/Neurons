<?PHP
namespace nrns\provider {
	
	class instanceProvider extends Provider {
		private $instance;
		
		public function __construct() {}

		public function setInstance($instance) {
			$this->instance = $instance;
		}
		
		public function __invoke(){
			return $this->instance;
		}
		
		
	}
	
}
?>