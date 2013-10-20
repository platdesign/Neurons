<?PHP 
	
	namespace uiRouter;
	use nrns;
	
	define(__NAMESPACE__."\\SEGMENT", __NAMESPACE__."\\segment");

	class uiRouteProvider extends nrns\provider\provider {
		
		// Load routeSetter-methods (when, get, put, post, delete)
		use \router\routeSetter;
		
		public function __construct($injection, $routeProvider) {
			$this->injection = $injection;
			$this->routeProvider = $routeProvider;
			$this->rootSegment = $this->injection->invokeClass(SEGMENT);
		}
		
		public function getRootSegment() {
			return $this->rootSegment;
		}
		
		
		
		
		private function addRoute($method, $route, $segmentChain) {
			$this->routeProvider->addRoute($method, $route, function($output, $injection, $scope, $output)use($segmentChain){
				
				$parts = explode(".", $segmentChain);
				
				$segment = $this->rootSegment;
				$mainView = null;
				foreach($parts as $part) {
					$segment = $segment->child($part);
					
					if(!$mainView) {
						$mainView = $view = $segment->getView($scope);
					} else {
						$view = $view->addSubview( $segment->getView($scope) );
					}
				}
				
				$output->setBody($mainView);
			});
		}
		
		
		
		/**
		 * Delegates the options for otherwise-route to the routeProvider-otherwise-method
		 *
		 * @param array $options 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function otherwise($options) {
			$this->routeProvider->otherwise($options);
		}
		
		
		
		public function segment($name, $options) {
			return $this->rootSegment->segment($name, $options);
		}
	
		public function within($name=null) {
			return $this->rootSegment->within($name);
		}
	}

?>