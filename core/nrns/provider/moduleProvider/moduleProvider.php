<?PHP 
	namespace nrns\provider;
	use nrns;

	class moduleProvider extends provider {
		
		private $coreModulesDir = "/modules";
		private $store;
		
		public function __construct($injection) {
			$this->injection = $injection;
			
			$this->store = $injection->invokeClass("nrns\\keyValStore");
		}

		private function moduleLoaded($name) {
			if( $this->store->get($name) ) {
				return true;
			}
		}

		public function loadModuleByFilename($filename) {
			if( file_exists($filename) ) {
				require_once $filename;
				return true;
			}
			return false;
		}
		
		public function loadCoreModuleByName($name) {
			if( !$this->moduleLoaded($name) ) {
				$filename = nrns::$nrnspath.$this->coreModulesDir.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."module.php";
			
				return $this->loadModuleByFilename($filename);
			}
			return false;
		}
		

		public function loadModules($modules=[]) {
			if( is_array($modules) ) {
				foreach($modules as $modulename) {
					
					if( strpos($modulename, ".php") OR strpos($modulename, ".PHP") ) {
						$this->loadModuleByFilename($modulename);
					} else {
						$this->loadCoreModuleByName($modulename);
					}
					
				}
			}
		}

		public function createModule($name, $deps=[]) {
			
			nrns::loadModules($deps);
			
			$module = $this->injection->invokeClass("nrns\module");
			
			$this->store->set($name, $module);
			return $module;
		}
		
		public function getModule($name) {
			return $this->store->get($name);
		}


		public function __invoke() {
			return new nrns\JSClosure(function($name){
				return $this->getModule($name);
			}, $this);
		}
	}
	
	

?>