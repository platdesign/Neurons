<?PHP
	
	$module = nrns::module('mysql', ['nrns']);
	
	$module->autoload();
	
	
	class mysql {
		
		public static function __callStatic($name, $args) {
	
			if(class_exists("mysql\\".$name)) {
				$ref = new \ReflectionClass("mysql\\".$name);
				return $ref->newInstanceArgs($args);
				
			}
			
		}
		
	}

?>