<?PHP namespace nrns;


class JSObject {
	
	public function __call($method, $args) {
		if( method_exists($this->{$method}, "apply") ) {
			return $this->{$method}->apply($this, $args);
		} else {
			throw new \Exception("Method not found");
		}
	}
	
	public function __set($key, $val) {
		if( is_callable($val) ) {
			$this->{$key} = new JSClosure($val);
		} else {
			$this->{$key} = $val;
		}
	}
	
}
?>