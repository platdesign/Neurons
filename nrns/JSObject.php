<?PHP 
	namespace nrns;
	use nrns;

	class JSObject {
		use events;

		public function __call($name, $args) {
			if( is_callable($this->{$name}) ) {
				return call_user_func_array($this->{$name}->bindTo($this), $args);
			} else {
				throw new \Exception('Method `'.$name.'` not found in class `'.get_class($this).'');
			}
		}
		
	}
	
?>