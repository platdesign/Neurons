<?PHP 
namespace nrns;


trait events {
	
	private $events_listener = [];
	
	public function on($key, $closure) {
		$this->events__listener[$key][] = $closure;
		return $this;
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
			
			$args = array_slice(func_get_args(), 1);
			
			foreach($this->events__listener[$prefix.$name] as $closure) {
				call_user_func_array($closure, $args);
			}
		}
		

		if(!$prefix) {
			$this->trigger("after:".$name);
		}
		
	}
	
	public function clearEvent($name) {
		unset($this->events__listener['before:'.$name]);
		unset($this->events__listener[$name]);
		unset($this->events__listener['after:'.$name]);
	}

	public function registerMultipleEvents($array=[], $scope=null) {
		if($scope) {
			foreach($array as $key => $val) {
				$this->on($key, $val->bindTo($scope));
			}
		} else {
			foreach($array as $key => $val) {
				$this->on($key, $val);
			}
		}
		
	}
}
?>