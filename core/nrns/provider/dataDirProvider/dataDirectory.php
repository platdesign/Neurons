<?PHP
namespace nrns {
	
	class dataDirectory implements \Iterator {
		public $stack = [];
		public $position = 0;
		public $splFileInfo;
	
		public function __construct($dir, $extensions) {
			if(is_string($extensions)) { $extensions = [$extensions]; }
			$this->extension = $extensions;
		
			if( is_string($dir) ) {
				$this->splFileInfo = new \splFileInfo($dir);
			}
		
				$iterator = new \DirectoryIterator($dir);
				foreach($iterator as $item) {

					if( substr($item->getFilename(), 0, 1) != "." ) {
					
						if($item->isDir()) {
							$group =  new dataDirectory\dataDirectoryGroup($item->getPathname(), $this->extension);
							$group->parent = $this;
							$this->stack[] = $group;
						}
					
						if($item->isFile() AND in_array($item->getExtension(), $this->extension) ) {
							$file = new dataDirectory\dataDirectoryItem($item->getPathname());
							$file->parent = $this;
							$this->stack[] = $file;
						}
					
					}
				
				}
		
		}
	
	
		/* FOREACH METHODS */
		public function rewind(){ $this->position = 0; }
	
		public function current(){ return $this->stack[$this->position]; }
	
		public function key(){ return $this->position; }
	
		public function next() { ++$this->position; }
	
		public function valid() { return isset($this->stack[$this->position]); }
	
		public function __get($key) {
			
			if(isset($this->{$key})) {
				return $this->{$key};
			} else {
				foreach($this as $item) {
					if(strtolower($item->getName()) == strtolower($key)) {
						return $item;
					}
				}
			}
		}
	
	}
	
}

namespace nrns\dataDirectory {


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

	class dataDirectoryGroup extends \nrns\dataDirectory {
	
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

	class dataDirectoryItemInfo {
	
		public function __construct($filename) {
			$infoJsonFile = $filename.".json";
		
			if(file_exists($infoJsonFile)) {
				$this->__autoinject(json_decode(file_get_contents($infoJsonFile)));
			}
		
		}
	
		private function __autoinject($obj) {
			foreach($obj as $key => $val) {
				$this->{$key} = $val;
			}
		}
	
	}

	
}
?>