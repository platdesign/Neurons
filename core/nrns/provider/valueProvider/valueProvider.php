<?PHP namespace nrns\provider;
	use nrns;

	class valueProvider extends provider {
		private $value;
		
		public function setValue($value) {
			$this->value = $value;
		}
		
		public function __invoke(){
			return $this->value;
		}
	
	}
	
	

?>