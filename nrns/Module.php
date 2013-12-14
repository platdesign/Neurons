<?PHP 
	namespace nrns;
	use nrns;

	class Module {
	
		use events;
	
		public $name, $deps;
		public function __construct($nrns, $_moduleName, $_moduleDeps) {
			$this->name = $_moduleName;
			$this->deps = array_merge($_moduleDeps, ['nrns']);
			
			
			$this->on('wakeup', function(){
				$this->trigger('init', $this);
				
			});
			
			$nrns->on('run', function(){
				$this->trigger('run');
			});
			
			$nrns->on('shutdown', function(){
				$this->trigger('shutdown');
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
				
				$provider = function()use($service) { 
					$p = nrns::$injection->invoke('nrns\provider\ServiceProvider');
					$p->setService(nrns::$injection->invoke($service));
					return $p;
				};
				
				nrns::$injection->provide($name, $provider, ['module'=>$this]);
				
			});
			return $this;
		}
		
		public function provider($name, $provider) {
			$this->on('after:init', function()use($name, $provider) {
				nrns::$injection->provide($name, $provider, ['module'=>$this]);
			});
			return $this;
		}
		
		public function factory($name, $closure) {
			$this->on('after:init', function()use($name, $closure) {
				
				$provider = function()use($closure) {
					$p = nrns::$injection->invoke('nrns\provider\FactoryProvider');
					$p->setClosure($closure);
					return $p;
				};
				
				nrns::$injection->provide($name, $provider, ['module'=>$this]);
				
			});
			return $this;
		}
	
		public function run($closure) {
			$this->on('run', function()use($closure){
				nrns::$injection->invoke($closure);
			});
			return $this;
		}
		
		public function shutdown($closure) {
			$this->on('shutdown', function()use($closure){
				nrns::$injection->invoke($closure);
			});
		}
	}
	
?>