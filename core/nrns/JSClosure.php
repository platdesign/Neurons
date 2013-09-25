<?PHP namespace nrns;


class JSClosure {
	
	private $scope;
	
	function __construct(callable $closure, $scope=null) {
		$this->closure = $closure;
		$this->changeScope($scope);
	}
	
	public function apply($scope=null, $args=[]) {
		$this->changeScope($scope);
		return call_user_func_array(@$this->closure->bindTo($this->scope), $args);
	}
	
	public function call($scope=null) {
		$this->changeScope($scope);
		return $this->apply($this->scope, []);
	}
	
	public function __invoke(){
		return $this->apply($this->scope, func_get_args());
	}
	
	private function changeScope($scope) {
		if( is_object($scope) ) {
			$this->scope = $scope;
		} else {
			$this->scope = $this;
		}
	}
}
?>