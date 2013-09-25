<?PHP namespace nrns\provider;
	use nrns;

	class factoryProvider extends provider {
		private $closure;
		
		public function __construct($injection) {
			$this->injection = $injection;
			$this->setClosure(function(){});
		}
		
		public function setClosure($closure) {
			$this->closure = $closure;
		}
		
		public function __invoke() {
			return $this->injection->invokeClosure($this->closure);
		}
		
	
	}
	
	

?>