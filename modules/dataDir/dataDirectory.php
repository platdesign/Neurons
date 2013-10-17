<?PHP
namespace dataDir;
use nrns;

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
							$group =  new dataDirectoryGroup($item->getPathname(), $this->extension);
							$group->parent = $this;
							$this->stack[] = $group;
						}
					
						if($item->isFile() AND in_array($item->getExtension(), $this->extension) ) {
							$file = new dataDirectoryItem($item->getPathname());
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
	

?>