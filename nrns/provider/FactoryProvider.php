<?PHP 
	namespace nrns\provider;
	use nrns;

	class FactoryProvider extends nrns\Provider {
		private $closure;
		
		public function __construct($injectionProvider) {
			$this->injectionProvider = $injectionProvider;
			$this->closure = function(){};
		}
		
		public function setClosure($closure) {
			$this->closure = $closure;
		}
		
		public function getService() {
			return $this->injectionProvider->invoke($this->closure);
		}
		
	}
	
?>