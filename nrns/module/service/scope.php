<?PHP

	namespace nrns;
	use nrns;
	
	
	
	class scope {
		
		private $parent;
		
		public function __construct() {
			$this->parent = $this;
		}
		
		public function newChild() {
			$clone = clone $this;
			$clone->parent = $this;
			return $clone;
		}
		
		public function parent() {
			return $this->parent;
		}
		
		public function __tostring() {
			return (string) json_encode($this, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
		}
		
	}

?>