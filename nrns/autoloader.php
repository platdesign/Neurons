<?PHP

namespace nrns;

class autoloader {
	private $watcher = [];
	
	public function __construct() {
		spl_autoload_register(function($class){
			$this->autoload($class);
		});
	}
	
	public function watch($path=null) {
		$this->watcher[] = dirname(debug_backtrace()[0]['file']).DIRECTORY_SEPARATOR.$path;
	}
	
	private function autoload($classname) {
		
		$classname = strtr($classname, '\\', DIRECTORY_SEPARATOR);
		
		$file = DIRECTORY_SEPARATOR.$classname.'.php';
		
		
		foreach($this->watcher as $path) {
			if( is_readable($path.$file) ) {
				require_once($path.$file);
			}
		}
		
	
		
	}
}

?>