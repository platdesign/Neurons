<?PHP 

namespace nrns;

class module {
	
	use events;
	
	public function __construct($nrns, $injection) {
		$this->nrns = $nrns;
		$this->injection = $injection;
	}
	
	public function config($closure) {
		$this->nrns->on("config", function()use($closure){
			\nrns::injectionProvider()->invokeClosure($closure);
		});
	}
	
	
	
	
	public function value($key, $val) {
		return $this->injection->provideValue($key, $val);
	}
	
	public function provider($key, $val) {
		return $this->injection->provideProvider($key, $val);
	}
	
	public function service($key, $val) {
		return $this->injection->provideService($key, $val);
	}
	
	public function factory($key, $val) {
		return $this->injection->provideFactory($key, $val);
	}
	
	
	
	
	
	public function controller($key, $closure) {
		
	}
	
	public function run($closure) {
		$this->nrns->on("run", $this->injection->getInvokedClosure($closure));
	}
	
}


?>