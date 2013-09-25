<?PHP namespace nrns;
	
	final class app extends provider\provider {
		private $listener;
		private $controller;

		public $__nrnspath;
		public $__rootpath;
		

		public function __construct($nrnspath, $rootpath) {
			$this->____createTime = microtime(1);
			
			$this->__nrnspath = $nrnspath;
			$this->__rootpath = $rootpath;
			
			$this->providerStore 	= new keyValStore();
			
			$this->controller 	= new keyValStore();
			$this->listener 	= new keyValStore();
			
			
			$this->injection 	= new provider\injectionProvider($this->providerStore, $this);
			
			
			$this->injection->invokeClosure(function($requestProvider, $routeProvider, $clientProvider){});
		}


		


		public function factory($name, $closure) {
			$factoryProvider = $this->injection->invokeClass("nrns\\provider\\factoryProvider");
			$factoryProvider->setClosure($closure);
			$this->injection->provide($name."Provider", $factoryProvider);
		}


		// Config
		public function config(callable $closure) {
			
			$this->addListener("before:start", function()use($closure){
			
				try {
					$this->injection->invokeClosure($closure->bindTo($this));
				} catch(\Exception $e) {
					\nrns::displaySysError($e->getMessage());
				}
				
			});
			
			return $this;
		}




		// Controllers
		public function controller($key, $closure=null){
			
			if( is_callable($closure) ) {
				
				if( $this->controller->get($key) ) {
					throw new \Exception("Controller ($key) already exists!");
				} else {
					
					$controller = function($scope)use($closure){
						return $this->injectionProvider->invokeClosure($closure, $scope);
					};
					$this->controller->set($key, $controller);
				}
				
			} else {
				return $this->callController($key, $closure);
			}
			
		}
		
		public function callController($key, $scope=null) {
			
			if( $controller = $this->controller->get($key) ) {
				
				if($scope===null){
					$scope = $this->createScope();
				}
				
				call_user_func_array($controller, [$scope]);
				return $scope;
				
			} else {
				throw new \Exception("Controller ($key) does not exist!");
			}
		}
		
		
		
		
		
		
		// Scoping
		public function createScope() {
			return new scope();
		}
		
		
		
		
		// Views
		public function view($key, $deps=[], $closure) {
			if( is_callable($closure) ) {
				$this->controller($key.".view", $deps, $closure);
			}
		}

		public function renderView($key, $scope) {
			return $this->controller($key.".view", $scope);
		}
		
		public function renderViewFromFile($filename, $scope) {
			$viewName = md5($filename)."_tempView";
			
			$this->view($viewName, ['scope'], function($scope)use($filename){
				require $filename;
			});
			
			return $this->renderView($viewName, $scope);
		}
		
		
		
		
		
		// Events
		public function addListener($name, $closure) {
			$ns = [];
			
			if( $oldNs = $this->listener->get($name) ) {
				$ns = $oldNs;
			}
			
			$ns[] = $closure;
			$this->listener->set($name, $ns);
		}
		
		public function triggerEvent($name) {
			
			if(substr($name, 0, 7)!="before:" AND substr($name, 0, 6)!="after:") {
				$triggerPreAfter = true;
			} else { $triggerPreAfter = false; }
			
			if( $triggerPreAfter ) {
				$this->triggerEvent("before:".$name);
			}
			
			$ns = $this->listener->get($name);
			
			if($ns) {
				foreach($ns as $closure) {
					call_user_func($closure);
				}
			}
			
			if( $triggerPreAfter ) {
				$this->triggerEvent("after:".$name);
			}
			
		}
		
		
		
		
		// BOOT THE APP
		public function close() {
			$this->triggerEvent("start");
			$this->triggerEvent("close");
		}
	
	
	
		public function execTime() {
			return microtime(1) - $this->____createTime;
		}
		
		public function requireLib($name) {
			$filename = $this->__nrnspath."/libs/".$name.".php";
			if( file_exists($filename) ) {
				require $filename;
			} else {
				throw new Exception("Lib not found ($name)");
			}
		}
		
		public function __invoke() {
			return $this;
		}
	}

?>