<?PHP namespace nrns\provider;
	use nrns;

	/**
	 * Provides a closure which is invoked and injected returned on service-request
	 *
	 * @package nrns
	 * @author Christian Blaschke
	 */
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