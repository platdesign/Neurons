<?PHP namespace nrns\provider;
	use nrns;

	class nrnsProvider extends provider {
		
		use nrns\events;
		
		public function __invoke(){
			return $this;
		}
	
		public function run() {
			$this->trigger("start");
			$this->trigger("run");
			$this->trigger("close");
			$this->trigger("shutdown");
		}
		
		public function getRootpath() {
			return nrns::$rootpath;
		}
		
		public function getSyspath() {
			return nrns::$nrnspath;
		}
	
		public function devMode() {
			return nrns::$devMode;
		}
	}
	
	

?>