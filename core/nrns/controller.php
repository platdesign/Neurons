<?PHP 
namespace nrns;


class controller {
	
	private $onCall;
	
	public function __construct($injection, $scope, $nrns){
		$this->scope = $scope;
		$this->injection = $injection;
		$this->nrns = $nrns;
		
		$this->onCall = function(){};
	}
	
	public function call() {
		return $this->injection->invokeClosure($this->onCall, $this->scope);
	}
	
	public function setClosure($closure) {
		$this->onCall = $closure;
	}
	
	public function setFile($filename) {
		
		$filename = $this->nrns->sanitizeRootPath($filename);
		
		if( file_exists($filename) ) {
			$closure = include $filename;
			$this->setClosure($closure);
		}
	}
}