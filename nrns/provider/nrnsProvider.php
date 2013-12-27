<?PHP 
	namespace nrns\provider;
	use nrns;

	class nrnsProvider extends nrns\Provider {
		
		public $devMode = false;
		
		public function __construct() {
			
			$this->on('runApp', function(){
				
				$this->trigger('bootstrap');
				$this->trigger('init');
				$this->trigger('run');
				$this->trigger('render');
				$this->trigger('shutdown');
				
			});
			
		}
		
		public function module($name) {
			return nrns::module($name);
		}
		
		public function newObject() {
			return new nrns\JSObject();
		}
	}
	
?>