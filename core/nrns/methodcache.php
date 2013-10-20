<?PHP
namespace nrns;

trait methodcache {
	
	private $methodcache;
	private $methodcache_prefix = "cached_";
	
	public function __call($method, $args) {
		if( !isset($this->methodcache) ) { $this->methodcache = new \stdClass; }
		
		$key = $method.serialize($args);
		if( isset($this->methodcache->{$key}) ) {
			return $this->methodcache->{$key};
		} else {
			
			if( method_exists($this, $this->methodcache_prefix.$method) ) {
				return $this->methodcache->{$key} = call_user_func_array([$this, $this->methodcache_prefix.$method], $args);
			} else {
				die("Method not found: ".$method);
			}
		}
		
	}
	
}

?>