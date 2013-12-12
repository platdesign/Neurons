<?PHP

	namespace nrns;
	use nrns;
	
	
	
	class scope {
		
		public function newChild() {
			$clone = clone $this;
			$clone->parent = $this;
			return $clone;
		}
		
	}

?>