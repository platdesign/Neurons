<?PHP namespace nrns\provider;
	use nrns;

	class datastoreProvider extends provider {
		
		private $__path;
		
		public function __construct($injection) {
			$this->injection = $injection;
			
			$this->__path = dirname(__FILE__)."/";
			
			
		}
		
		public function createConnection($name, $configArray = []) {
			
			$driver = $configArray['driver'];
			
			if(isset($driver)) {
				
				$driverclass = get_class()."\\driver\\".$driver;
				
				$driver = new $driverclass();
				
			}
			
			
		}
		
	}
	
	

?>