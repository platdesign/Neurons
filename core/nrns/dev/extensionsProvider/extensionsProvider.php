<?PHP namespace nrns\provider;
use nrns;

require "extension.php";


	
	class extensionsProvider extends provider {
		
		public function __construct($injection) {
			$this->injection = $injection;
		}
		
		private function __loadExtension($classname) {
			if( class_exists($classname) AND is_subclass_of($classname, "\\nrns\\extension") ) {
				$this->injection->invokeClass($classname);
			} else {
				throw new \Exception("Extension not found ($classname)");
			}
		}
		
		public function load($extensions) {
			if(!is_array($extensions)) { $extensions = [$extensions]; }
			
			foreach($extensions as $extension) {
				$this->__loadExtension($extension);
			}
		}
	
	}	

?>