<?PHP 
namespace nrns;


trait events {
	
	private $events_listener = [];
	
	public function on($key, $closure) {
		$this->events__listener[$key][] = $closure;
	}
	
	public function trigger($name) {
		
		$prefix = null;
		
		if( strpos($name, ":") ) {
			list($prefix, $name) = explode(":", $name);
			$prefix .= ":";
		}

		if(!$prefix) {
			$this->trigger("before:".$name);
		}
		
		if( isset($this->events__listener[$prefix.$name]) ) {
			foreach($this->events__listener[$prefix.$name] as $closure) {
				call_user_func($closure);
			}
		}
		

		if(!$prefix) {
			$this->trigger("after:".$name);
		}
		
	}
}
?>