<?PHP 
	namespace nrns\provider;
	use nrns;

	class nrnsProvider extends nrns\Provider {
		
		public $devMode = false;
		
		public function __construct() {
			
			$this->on('runApp', function(){
				
				$this->trigger('init');
				$this->trigger('run');
				$this->trigger('render');
			});
			
		}
		
		public function module($name) {
			return nrns::module($name);
		}
	}
	
?>