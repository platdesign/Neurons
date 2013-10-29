<?PHP 
	
	namespace segments;
	use nrns;
	

	class segmentProvider extends nrns\provider\provider {
		
		// Load routeSetter-methods (when, get, put, post, delete)
		use \router\routeSetter;
		
		public $rootSegment;
		private $rootName = "root";
		
		public function __construct($routeProvider, $injection, $response, $request) {
			$this->routeProvider 	= $routeProvider;
			$this->injection 		= $injection;
			$this->response 		= $response;
			$this->request			= $request;
			
			$this->rootSegment = new segment( $this->rootName );
		}
	
		public function segment($name, $options=[]) {
			$segment = $this->rootSegment->createChild($name);
			$segment->setOptions($options);
			return $segment;
		}
	
		public function addRoute($method, $route, $segmentChain) {
			
			$this->routeProvider->addRoute($method, $route, function($scope)use($segmentChain){
				$this->renderSegment($segmentChain, $scope);
			});

		}
		
		public function otherwise($segmentChain) {
			$this->routeProvider->otherwise(function($scope)use($segmentChain){
				$this->renderSegment($segmentChain, $scope);
			});
		}
		
		
		public function renderSegment($segmentChain, $scope) {
			
			$doc = \html::doc();
			$doc->head->setBase($this->request->getBase()."/");
			
			
			
			
			$segment = $this->rootSegment->find($segmentChain);

			$segments =  array_slice( $segment->getSegmentsFromRoot() , 1);
			
			$mainView = $view = nrns::view($scope);
			
			
			foreach($segments as $key => $seg) {
				$view->setGlobal("doc", $doc);
				$this->response->setCode( $seg->code );
				
				
				// Controller
				if( isset($seg->controller) OR isset($seg->controllerUrl) ) {
					
					$controller = nrns::controller($scope);
					
					if( isset($seg->controller) ) {
						$controller->setClosure( $seg->controller );
					} else if( isset($seg->controllerUrl) ) {
						$controller->setFile( $seg->controllerUrl );
					}
					
					$controller->call();
				}
				
				
				// Template
				if( isset($seg->templateUrl) ) {
					$view->setTemplateUrl($seg->templateUrl);
				}
				
				
				if( $seg !== end($segments) ) {
					$newView = nrns::view($scope);
					$view->setGlobal("segment", $newView);
					$view = $newView;
				}
				
				
			}
			

			$doc->body->setContent($mainView);
			
			$this->response->setContentType('text/html');
			$this->response->setBody($doc);
			
		}


		public function getChainOfSegment($segment) {
			$chain = $segment->getChain();
			
			return ltrim($segment->getChain(), $this->rootName.'.');
		}
	}
	
	

?>