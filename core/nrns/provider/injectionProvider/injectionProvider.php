<?PHP namespace nrns\provider;
	
	use nrns;
	
	class injectionProvider extends provider {
		

		public function __construct($providerStore, $app) {
			$this->app 		= $app;
			$this->store 	= $providerStore;
			$this->store->set("injectionProvider", $this);
			$this->store->set("appProvider", $app);
		}
		
		public function getProvider($name) {
			return $this->__getProvider($name);
		}
		
		public function getProviders($names=[]) {
			return $this->__getProviders($names);
		}
		
		public function getDeps($deps=[], $scope=null) {
			if( is_string($deps) ) { $deps = [$deps]; }
			
			$result = [];
			foreach($deps as $dep) {
				
				if(strtolower($dep) == "scope") {
					if($scope==null) {
						$scope = $this->app->createScope();
					}
					$result[] = $scope;
				} else {
					if( strpos($dep, $this->__suffix) ) {
						$result[] = $this->__getProvider($dep);
					} else {
						$result[] = $this->__getService($dep);
					}
				}
			}
			return $result;
		}
		
		
		
		public function invokeClosure($closure, $scope=null) {
			$r = new \ReflectionFunction($closure);

			$deps = $this->params2deps($r->getParameters());
			
			$injections =  $this->getDeps($deps, $scope);
			return call_user_func_array($closure, $injections);
		}
		
		public function invokeClass($classname, $scope=null) {
			
			if( class_exists($classname) ) {
				$deps = [];
				if( method_exists($classname, "__construct") ) {
					$r = new \ReflectionMethod($classname, "__construct");
					$deps = $this->params2deps( $r->getParameters() );
				}
			
				$args = $this->getDeps($deps, $scope);
			
				$r = new \ReflectionClass($classname);
				return $r->newInstanceArgs($args);
			} else {
				throw new \Exception("Class ($classname) does not exist");
			}
			
			
		}
		
		public function invokeMethod($object, $method, $scope=null) {
			$r = new \ReflectionMethod($object, $method);
			$params = $r->getParameters();
			
			$deps = [];
			foreach($params as $param) {
				$deps[] = $param->name;
			}
			$injections =  $this->getDeps($deps, $scope);
			return call_user_func_array([$object, $method], $injections);
		}
		
		public function getInvokedClosure($closure, $scope=null) {
			return function()use($scope, $closure) {
				return $this->invokeClosure($closure, $scope);
			};
		}
		
		
		public function provide($name, $providerInstance) {
			$this->__registerProvider($name, $providerInstance);
		}

		
		private $__suffix = "Provider";
		
		private function __getProvider($name) {
			if( strlen($name) > 0 ) {
				if( !strpos($name, $this->__suffix) ) { $name = $name.$this->__suffix; }
			
				if( $provider = $this->store->get($name) ) {
					return $provider;
				} else {
					if( !$provider = $this->__instanciateProvider($name) ) {
						throw new \Exception("UNABLE TO INSTANCIATE PROVIDER! ($name)");
					} else {
						return $provider;
					}
				}
			} else {
				throw new \Exception("Unknown Providername");
			}
		}

		private function __getProviders($names=[]) {
			$result = [];
			
			foreach($names as $name) {
				$result[] = $this->__getProvider($name);
			}
			return $result;
		}
		
		private function __getService($name) {
			$provider = $this->__getProvider($name);
			
			return $provider->getService();
		}
		
		private function __getServices($names=[]) {
			$result = [];
			
			foreach($names as $name) {
				$result[] = $this->__getService($name);
			}
		}
		
		private function __registerProvider($name, $provider) {
			$this->store->set($name, $provider);
		}
		
		private function __triggerInit($provider) {
			if(method_exists($provider, "__init")) {
				$this->invokeMethod($provider, "__init");
			}
		}
		
		private function __instanciateProvider($name) {
			$provider=NULL;
			
			/* Check if $name is a Provider-Class-(name) AND instanciates it */

			$classname = '\\nrns\\provider\\'.$name;
			
			if( class_exists($classname) AND is_subclass_of($classname, "\\nrns\\provider\\provider") ) {
					
				$provider = $this->invokeClass($classname);
				$this->__registerProvider($name, $provider);
				$this->__triggerInit($provider);
				return $provider;

			}	
			
			
			/* Check if $name is a class and instanciates a ClassProvider, 
			which returns as Service a new instance of this class */
			if(!$provider) {
				$classname = '\\nrns\\'.str_replace("Provider", "", $name);
					
				if( class_exists($classname) AND !is_subclass_of($classname, "\\nrns\\provider\\provider")  ) {
					$name = $classname.$this->__suffix;
					
					$provider = $this->invokeClass("\\nrns\\provider\\classProvider");
					$provider->setClassname($classname);
					$this->__registerProvider($name, $provider);
					$this->__triggerInit($name);
					
					return $provider;
					
				}
				
			}
			
		}
		
		private function params2deps($params) {
			$deps = [];
			foreach($params as $param) {
				$deps[] = $param->name;
			}
			return $deps;
		}
		
	}
	


?>