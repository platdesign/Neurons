<?PHP 
	namespace nrns;
	use nrns;

	class Module {
	
		use events;
	
		public $name, $deps;
		public function __construct($nrnsProvider, $_moduleName, $_moduleDeps) {
			$this->name = $_moduleName;
			$this->deps = array_merge($_moduleDeps, ['nrns']);
			
			
			$this->on('wakeup', function(){
				$this->trigger('init');
				
			});
			
			$nrnsProvider->on('run', function(){
				$this->trigger('run');
			});
			
		}
	
		public function config($closure) {
			$this->on('init', function()use($closure){
				nrns::$injection->invoke($closure->bindTo($this), ['module'=>$this]);
			});
			return $this;
		}
	
		public function service($name, $service) {
			$this->on('after:init', function()use($name, $service) {
				$provider = new nrns\provider\ServiceProvider();
				
				$provider->setService(nrns::$injection->invoke($service));
				
				return nrns::$injection->provide($name, $provider, ['module'=>$this]);
				
			});
			return $this;
		}
		
		public function provider($name, $provider) {
			$this->on('after:init', function()use($name, $provider) {
				nrns::$injection->provide($name, $provider, ['module'=>$this]);
			});
			return $this;
		}
		
		public function factory($name, $factory) {
			return $this;
		}
	
		public function run($closure) {
			$this->on('run', function()use($closure){
				nrns::$injection->invoke($closure);
			});
			return $this;
		}
	}
	
?>