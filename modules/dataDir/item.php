<?PHP
	namespace dataDir;
	use nrns;


	class dataDirectoryItem {
		public $splFileInfo;
	
		public function __construct($filename) {
		
			$this->splFileInfo = new \splFileInfo($filename);
		
			$this->info = new dataDirectoryItemInfo($this->splFileInfo->getPathname());
		}
	
		public function getName() {
			return basename($this->spl()->getFilename(), ".".$this->spl()->getExtension());
		}
	
		public function getTitle() {
			if( isset($this->info->title) ) {
				return $this->info->title;
			} else {
				return $this->getName();
			}
		}
	
		public function getContent() {
			return file_get_contents($this->spl()->getPathname());
		}
		
		public function lastUpdate() {
			return $this->spl()->getMTime();
		}
		
		public function spl() {
			return $this->splFileInfo;
		}
		
		public function __tostring() {
			return $this->getTitle();
		}
	}
?>