<?PHP
namespace nrns\provider {
	
	class classProvider extends Provider {
		private $classname;
		
		public function __construct($injection) {
			$this->injection = $injection;
		}

		public function setClassname($classname) {
			$this->classname = $classname;
		}
		
		public function __invoke(){
			return $this->injection->invokeClass($this->classname);
		}
		
		
	}
	
}
?>