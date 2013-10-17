<?PHP
	namespace dataDir;
	use nrns;

	class dataDirectoryGroup extends dataDirectory {
	
		public function __construct($dirname, $extensions){
			parent::__construct($dirname, $extensions);
			$this->info = new dataDirectoryItemInfo($dirname);
		}
	
		public function __tostring() {
			return $this->getTitle();
		}
	
		public function getTitle() {
			if( isset($this->info->title) ) {
				return $this->info->title;
			} else {
				return $this->getName();
			}
		}
	
		public function getName() {
			return basename($this->splFileInfo->getFilename());
		}
	}
	

?>