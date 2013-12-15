<?PHP 
	namespace nrns\provider;
	use nrns;
	use _;
	
	class injectionProvider extends nrns\Provider {
	
		public function __construct() {
			$this->providers = new nrns\keyValStore();
			
			$this->provide('injectionProvider', $this);
		}
	
		
	
		public function annotate($params, $locals=[]) {
			$args = [];
			foreach($params as $key) {
			
				if( isset($locals[$key]) ) {
					$args[$key] = $locals[$key];
				} else {
					
					$provider = $this->provider($key);
					
					// Let the provider know that it will be injected now
					$provider->trigger('inject');
					
					if(strpos($key, 'Provider')) {
						// Return provider
						$args[$key] = $provider;
					} else {
						// Return Service of Provider
						$args[$key] = $provider->getService();
					}
					
				}
			
			}
			return $args;
		}
	
		public function invoke($thing, $locals=[]) {
			if(is_array($thing)) {
				$params 	= _::slice($thing, 0, -1);
				$thing 		= _::last($thing);
			}
		
			if( is_string($thing) ) {
				if( is_callable($thing) ) {
				
					$params = isset($params) ? $params : _::properties($thing);
					return _::invoke($thing, $this->annotate($params, $locals));
				
				} else if( class_exists($thing) ) {
				
					if( !isset($params) ) {
						if( method_exists($thing, '__construct') ) {
							$ref = new \ReflectionMethod($thing, "__construct");
							$params = _::pluck($ref->getParameters(), 'name');
						} else {
							$params = [];
						}
					}
				
					$ref = new \ReflectionClass($thing);
					return $ref->newInstanceArgs( $this->annotate($params, $locals) );
				
				}
			} else if( is_callable($thing) ) {
				$params = isset($params) ? $params : _::properties($thing);
				return _::invoke($thing, $this->annotate($params, $locals));
			}
		
		}
	
		public function provide($name, $thing, $locals=[]) {
			
			$name = $this->sanitizeProviderName($name);
			
			$provider = function()use($thing, $locals){
				return is_a($thing, 'nrns\Provider') ? $thing : $this->invoke($thing, $locals);
			};
		
			$this->providers->set($name, $provider);
		}
	
		public function provider($name) {
			$name = $this->sanitizeProviderName($name);
			
			
			if($provider = $this->providers->get($name)) {
				
				if(is_callable($provider)) {
					$provider = call_user_func($provider);
					$this->providers->set($name, $provider);
				}
				
				return $provider;
				
				
			} else {
				throw nrns::Exception('Provider "'.$name.'" not found!');
			}
			
		}
		
		public function service($name) {
			return $this->provider($name)->getService();
		}
	
	
		private function sanitizeProviderName($name) {
			
			
			if(!strrpos($name, 'Provider')) {
				$name .= 'Provider';
			}
			
			
			return $name;
		}
		
		
		public function getInstantiatedProviders() {
			$result = [];
			foreach($this->providers->getAll() as $key => $val) {
				if(!is_callable($val)) {
					$result[] = $key;
				}
			}
			return $result;
		}
	}

	
	
?>