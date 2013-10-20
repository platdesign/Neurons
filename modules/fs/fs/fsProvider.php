<?PHP
namespace nrns;
use nrns;

require "fsItemInfo.php";
require "fsItem.php";
require "fsDir.php";
require "fsFile.php";

class fsProvider extends nrns\provider\provider {
	
	private $rootDir;
	
	function __construct(){

	}
	
	public function setRootPath($path) {
		$this->rootDir = new fsDir($path);
	}

	public function __invoke() {
		return $this->rootDir;
	}
	
}


?>