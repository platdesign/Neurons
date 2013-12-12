<?PHP 
	namespace nrns\provider;
	use nrns;

	class moduleProvider extends nrns\Provider {
		private $modules;
	
		public function __construct() {
			$this->modules = new nrns\keyValStore();
		}
	
		public function create($name, $deps=[]) {
			$module = nrns::$injection->invoke('nrns\Module', ['_moduleName'=>$name, '_moduleDeps'=>$deps]);
		
			$this->modules->set($name, $module);
			return $module;
		}
	
		public function get($name) {
			return $this->modules->get($name);
		}
	
		public function wakeUpModules() {
			$deps = new nrns\Dependencies();
		
			foreach($this->modules->getAll() as $module) {
				$deps->add($module->name, $module->deps);
			}
		
			$sorted = $deps->sort();
		
			foreach($sorted as $dep) {
				$this->modules->get($dep->name)->trigger('wakeup');
			}
		}
		
	}
	
?>