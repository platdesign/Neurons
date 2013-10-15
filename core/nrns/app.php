<?PHP namespace nrns;
	
	final class app extends provider\provider {
		
		// Load events-trait
		use events;
		
		private $listener;
		private $controller;

		public $__nrnspath;
		public $__rootpath;
		

		public function __construct($nrnspath, $rootpath) {
			$this->____createTime = microtime(1);
			
			$this->__nrnspath = $nrnspath;
			$this->__rootpath = $rootpath;
			
			
			$this->controller 	= new keyValStore();
			
			
			$this->initInjection();
			
			
			$this->dev(FALSE);
			
			
		}

		public function modules($array=[]) {
			foreach($array as $module) {
				$classname = "nrns\\module\\".$module;
				if( class_exists($classname) ) {
					
					$this->injection->invokeClass($classname);
				}
			}
		}

		private function initInjection() {
			$this->injection 	= new provider\injectionProvider();
			$this->injection->provideInstance("app", $this);
		
		}


		public function dev($bool=FALSE) {
			switch($bool){
				case TRUE:
					error_reporting(-1);
				break;
				case FALSE:
					error_reporting(0);
					set_error_handler(function($code, $message, $file, $line){
						return true;
					});
					
					register_shutdown_function(function(){
						return true;
					});
				break;
			}
		}


		
		public function factory($name, $closure) {
			return $this->injection->provideFactory($name, $closure);
		}

		public function service($name, $closure) {
			
			$this->on("before:start", function()use($name, $closure) {
				$this->injection->provideValue($name, call_user_func($closure));
			});
			
		}
		
		
		

		// Config
		public function config(callable $closure) {
			
			$this->on("before:start", function()use($closure){
			
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
						return $this->injection->invokeClosure($closure, $scope);
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
		
		
		
		
		public function onStart($closure) {
			$this->addListener("before:start", function()use($closure){
				$this->injection->invokeClosure($closure->bindTo($this));
			});
			return $this;
		}
		
		public function onClose($closure) {
			$this->addListener("before:close", function()use($closure){
				$this->injection->invokeClosure($closure->bindTo($this));
			});
			return $this;
		}
		
		
		
		
		// Scoping
		public function createScope() {
			return new scope();
		}
		
		
		
	
		
		
		
		// BOOT THE APP
		public function close() {
			$this->trigger("start");
			$this->trigger("close");
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