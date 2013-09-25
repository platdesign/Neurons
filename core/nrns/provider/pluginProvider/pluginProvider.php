<?PHP namespace nrns\provider;
	
require "plugin.php";
	
	class pluginProvider extends provider {

		public function __construct($injection) {
			$this->injection = $injection;
		}
		
		public function __invoke() {
			return \nrns::closure(function($name, $args=[]){
		
				if( class_exists($name) AND is_subclass_of($name, "\\nrns\\plugin") ) {
					$plugin = $this->injection->invokeClass($name);
					
					if( method_exists($plugin, "__invoke") ) {
						call_user_func_array([$plugin, "__invoke"], $args);
					}
					return $plugin;
				}
				
			}, $this);
		}
	}
	

?>