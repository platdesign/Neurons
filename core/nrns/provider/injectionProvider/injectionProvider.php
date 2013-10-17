<?PHP 

	namespace nrns\provider;
	use nrns;
	
	class injectionProvider extends provider {
		
		/**
		 * provider-instance-store
		 *
		 * @var string
		 */
		private $store;
		
		
		/**
		 * Suffix for each Providername stored in the key-val-store
		 *
		 * @var string
		 */
		private $providerSuffix = "Provider";



		public function __construct() {
			$this->store = new nrns\keyValStore();
			
			$this->provide("injection", $this);
		}
		
		
		
		
		/**
		 * undocumented function
		 *
		 * @param string $key 
		 * @param string $val 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function provideProvider($key, $val) {
			$instance = null;
			if( is_string($val) ) {
				
				if( class_exists($val) ) {
					$instance = $this->invokeClass($val);
				} else if( is_callable($val) ) {
					$instance = $this->invokeClosure($val);
				}
				
			} else if(is_callable($val)) {
				$instance = $this->invokeClosure($val);
			}
			
			
			
			return $this->provide($key, $instance);
			
		}
		
		
		
		/**
		 * undocumented function
		 *
		 * @param string $key 
		 * @param string $val 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function provideService($key, $val) {
			if( is_string($val) ) {
				
				if( class_exists($val) ) {
					$service = $this->invokeClass($val);
				} else if( is_callable($val) ) {
					$service = $this->invokeClosure($val);
				}
				
			} else if( is_callable($val) ) {
				$service = $this->invokeClosure($val);
			}
			
			return $this->provideValue($key, $service);
		}



		/** 
		 * Creates a new provider which returns the $val-param as its' service.
		 * The Provider is also stored into providerStore with the $key-param
		 *
		 * @param string $key 
		 * @param string $val 
		 * @return instance of valueProvider
		 * @author Christian Blaschke
		 */
		public function provideValue($key, $val) {
			$provider = $this->invokeClass("nrns\\provider\\valueProvider");
			$provider->setValue($val);
			
			$this->provide($key, $provider);
			return $provider;
		}



		/**
		 * Creates a new provider which returns the result of the invoked $closure->param as its' service.
		 * The Provider is also stored into providerStore with the $key-param
		 *
		 * @param string $key 
		 * @param string $closure 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function provideFactory($key, $val) {
			
			
			if( is_string($val) ) {
				if( class_exists($val) ) {
					$closure = function()use($val) {
						return $this->invokeClass($val);
					};
				} else if( is_callable($val) ) {
					$closure = function()use($val) {
						return $this->invokeClosure($val);
					};
				}
			} else if( is_callable($val) ) {
				$closure = $val;
			}
			
			
			$provider = $this->invokeClass("nrns\\provider\\factoryProvider");
			
			$provider->setClosure($closure);
			
			$this->provide($key, $provider);
			return $provider;
		}



		/**
		 * Stores a Provider-Instance in the key-val-store
		 * Checks if ProviderSuffix is available in $name. If not: Adds it.
		 *
		 * @param string $name 
		 * @param string $providerInstance 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function provide($name, $providerInstance) {
			
			if( is_object($providerInstance) ) {
				if(is_subclass_of($providerInstance, "nrns\\provider\\provider")) {
					
					$name = $this->santizeProviderName($name);
			
					$this->store->set($name, $providerInstance);
			
			
					/* Call init method if exists on providerInstance */
					if(method_exists($providerInstance, "init")) {
						$this->invokeMethod($providerInstance, "init");
					}
					return $providerInstance;
					
				} else {
					throw new \Exception("Instance of <b>$key</b> has to be a subclass of nrns\\provider");
				}
			} else {
				return false;
			}
			
			
			
		}



		/**
		 * Checks if the provider-$name has the provider-suffix at the end.
		 * If not it's been added. The sanitized $name will be returned.
		 *
		 * @param string $name 
		 * @return string sanitized name
		 * @author Christian Blaschke
		 */
		private function santizeProviderName($name) {
			if( !strpos($name, $this->providerSuffix) ) { 
				$name .= $this->providerSuffix; 
			}
			return $name;
		}



		/**
		 * Returns a Provider-instance
		 * If the Provider-Instance with the name ($name) is available at the store, it is returned.
		 * Ohterwise this method will try to instanciate and provide it. 
		 * In this case the string in $name is the name for the provider-class which is looked up.
		 *
		 * @param string $name 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function getProvider($name) {
			$name = $this->santizeProviderName($name);
			
			
			// If there is a provider-instance in the store return it
			if( $provider = $this->store->get($name) ) {
				return $provider;
				
			} else {
			// Otherwise try to instanciate the provider from a provider-class automatically
			
				if( $provider = $this->provideProvider($name, '\\nrns\\provider\\'.$name) ) {
					return $provider;
				} else {
					
					throw new \Exception("Unable to instanciate provider [$name]");
				}
			}
		}



		/**
		 * Converts the names of the expected dependencys into provider or services.
		 * If the provider-suffix is at the end of a dependency-name, a provider is expected and added to the result-array.
		 * Otherwise the service of the dependency-name + provider-suffix is expected and added to the result-array.
		 *
		 * @param Array $deps (Array of strings which are the names of the expected dependencys)
		 * @param Object $scope 
		 * @return Array of dependencys
		 * @author Christian Blaschke
		 */
		public function getDeps(Array $deps=[], $scope=null) {
			$result = [];
			
			// $deps has to be an array. If $deps is only a string: convert into an array
			if( is_string($deps) ) { $deps = [$deps]; }
			
			
			foreach($deps as $dep) {
				
				if(strtolower($dep) == "scope") {
					if($scope==null) {
						
						$scope = $this->invokeClass("nrns\\scope");
						
						//$scope = $this->getProvider("app")->getService()->createScope();
					}
					$result[] = $scope;
				
				} else {
				
					// If the Provider-Suffix is not in the dependency-name the service is expected
					if( strpos($dep, $this->providerSuffix) ) {
						$result[] = $this->getProvider($dep);
					} else {
						$result[] = $this->getProvider($dep)->getService();
					}
					
				}
			}
			
			return $result;
		}


		/**
		 * Invokes and injects automatically closures, classes, object-methods, static-methods
		 *
		 * @param variable $input 
		 * @param variable $optional 
		 * @return result of each task
		 * @author Christian Blaschke
		 */
		public function invoke($input, $optional=null) {
			// Closure
			if( is_callable($input) AND !is_string($input) ) {
				return $this->invokeClosure($input, $optional);
			}
			
			// Instance-Method
			if( is_array($input) ) {
				return $this->invokeMethod($input[0], $input[1], $optional);
			}
			
			// Static-Method
			if( is_string($input) AND strpos($input, "::") ) {
				return $this->invokeStaticMethod($input, $optional);
			}
			
			// Class
			if( is_string($input) AND class_exists($input) ) {
				return $this->invokeClass($input, $optional);
			}
		}
		
		
		
		/**
		 * Invokes and injects a given closure
		 *
		 * @param string $closure 
		 * @param object $scope
		 * @return result of the invoked closure
		 * @author Christian Blaschke
		 */
		public function invokeClosure($closure, $scope=null) {
			$r = new \ReflectionFunction($closure);
			
			$deps = array_filter($r->getParameters(), function(&$item){
				$item = $item->name;
				return true;
			});
			
			
			$injections =  $this->getDeps($deps, $scope);
			return call_user_func_array($closure, $injections);
		}
		
		
		
		/**
		 * Initiates and injects a given class
		 *
		 * @param string $classname 
		 * @param object $scope
		 * @return object instance of class
		 * @author Christian Blaschke
		 */
		public function invokeClass($classname, $scope=null) {
			
			if( class_exists($classname) ) {
				$deps = [];
				if( method_exists($classname, "__construct") ) {
					$r = new \ReflectionMethod($classname, "__construct");
					$deps = array_filter($r->getParameters(), function(&$item){
						$item = $item->name;
						return true;
					});
				}
			
				$args = $this->getDeps($deps, $scope);
			
				$r = new \ReflectionClass($classname);
				return $r->newInstanceArgs($args);
			} else {
				throw new \Exception("Class ($classname) does not exist");
			}
			
			
		}
		
		
		
		/**
		 * Invokes and injects a given method of an object-instance
		 *
		 * @param string $object 
		 * @param string $method
		 * @param object $scope
		 * @return result of the invoked method
		 * @author Christian Blaschke
		 */
		public function invokeMethod($object, $method, $scope=null) {
			$r = new \ReflectionMethod($object, $method);
			$params = $r->getParameters();
			
			$deps = array_filter($r->getParameters(), function(&$item){
				$item = $item->name;
				return true;
			});
			
			$injections =  $this->getDeps($deps, $scope);
			return call_user_func_array([$object, $method], $injections);
		}
		
		
		
		/**
		 * Invokes and injects a given static method
		 *
		 * @param string $method (class::method) 
		 * @param object $scope
		 * @return result of the invoked static method
		 * @author Christian Blaschke
		 */
		public function invokeStaticMethod($method, $scope=null) {
			$ex = explode("::", $method);

			return $this->invokeMethod($ex[0], $ex[1], $scope);
		}
		
		
		
		/**
		 * Returns a Closure which invokes the given closure
		 *
		 * @param string $closure 
		 * @param object $scope
		 * @return closure
		 * @author Christian Blaschke
		 */
		public function getInvokedClosure($closure, $scope=null) {
			return function()use($scope, $closure) {
				return $this->invokeClosure($closure, $scope);
			};
		}
		
		
		/**
		 * Little debug-method to get an array of all active ProviderNames
		 *
		 * @return Array
		 * @author Christian Blaschke
		 */
		public function getActiveProviders() {
			return array_keys($this->store->getAll());
		}
		
	}
	


?>