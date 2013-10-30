<?PHP
	namespace nrns;
	use nrns;

	

	class fsDir extends \splFileInfo {
	
		use fsItem;
	
	
		public function __construct($path) {
			parent::__construct($path);
		
			return $this;
		}
	
	
		/**
		 * Returns an DirectoryIterator for this dir-path
		 *
		 * @return DirectoryIterator
		 * @author Christian Blaschke
		 */
		public function getIterator(){
			return new \DirectoryIterator((string) $this);
			
		}
	
	
	
	
		/**
		 * TODO: undocumented function
		 *
		 * @param string $itemFilter 
		 * @param string $sortClosure 
		 * @return void
		 * @author Christian Blaschke
		 */
		private function _itemsFilterSort($itemFilter, $sortClosure) {
			$result = [];
			
			foreach($this->getIterator() as $item) {
				if( call_user_func_array($itemFilter, [$item]) AND !$item->isDot() AND substr($item, 0, 1) != ".") {
					$result[] = $this->find( $item->getFilename() );
				}
			}
			
			uasort($result, $sortClosure);
			
			return $result;
		}
	
	
	
	
	
	
		/**
		 * GENERATOR: For all Files and Directories
		 *
		 * @return void
		 * @author Christian Blaschke
		 */
		public function cached_items() {
			
			return $this->_itemsFilterSort(function($item)use($ext){
				
				return true;
				
			}, function($a, $b){
				return strnatcmp($a, $b);
			});
			
			/* OLD Generator
			foreach($this->getIterator() as $item) {
				if( !$item->isDot() AND substr($item, 0, 1) != "." ) {
					yield $this->find( $item->getFilename() );
				}
			}
			return;
			*/
		}
	
	
	
		/**
		 * GENERATOR: For Files
		 *
		 * @return void
		 * @author Christian Blaschke
		 */
		public function cached_files() {
			
			return $this->_itemsFilterSort(function($item)use($ext){
				
				if( $item->isFile() ) {
					return true;
				}
				
			}, function($a, $b){
				return strnatcmp($a, $b);
			});
			
			/* OLD Generator
			foreach($this->getIterator() as $item) {
				if( $item->isFile() AND !$item->isDot() AND substr($item, 0, 1) != "." ) {
					yield $this->find( $item->getFilename() );
				}
			}
		
			return;
			*/
		}
	
	
	
		/**
		 * GENERATOR: For Files with Extension
		 *
		 * @return void
		 * @author Christian Blaschke
		 */
		public function cached_filesExt($ext) {

			return $this->_itemsFilterSort(function($item)use($ext){
				
				$itemExt = strtolower(basename($item->getExtension()));
				
				if( $itemExt === $ext AND $item->isFile() ) {
					return true;
				}
				
			}, function($a, $b){
				return strnatcmp($a, $b);
			});
		
		}
	
	
		/**
		 * GENERATOR: For Directories
		 *
		 * @return void
		 * @author Christian Blaschke
		 */
		public function cached_dirs() {
			
			return $this->_itemsFilterSort(function($item){
				
				if( $item->isDir() ) {
					return true;
				}
				
			}, function($a, $b){
				return strnatcmp($a, $b);
			});
			
			/* OLD GENERATOR
			foreach($this->getIterator() as $item) {
				if( $item->isDir() AND !$item->isDot() AND substr($item, 0, 1) != "." ) {
					yield $this->find( $item->getFilename() );
				}
			}
			return;
			*/
		}



		/**
		 * Checks if a File/Dir exists
		 *
		 * @param string $name 
		 * @return bool
		 * @author Christian Blaschke
		 */
		public function exists($name) {
			if( $this->find($name) ) {
				return true;
			}
			return false;
		}



		/**
		 * Finds a File/Dir or returns false
		 *
		 * @param string $name 
		 * @return fsFile | fsDir | false
		 * @author Christian Blaschke
		 */
		public function cached_find($name) {
			$path = (string) $this.DIRECTORY_SEPARATOR.$name;
		
			if(isset($this->{$name})) {
				return $this->{$name};
			} else {
				if( is_dir($path) ) {
					$this->{$name} = new fsDir($path);
					$this->{$name}->setParent($this);
					return $this->{$name};
				} else if( file_exists($path) ){
					$this->{$name} = new fsFile($path);
					$this->{$name}->setParent($this);
					return $this->{$name};
				} else {
					return null;
				}
			}
		
		}
	
	
		/**
		 * Alias for find($name) 
		 *
		 * @param string $name 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function __get($name) {
			return $this->find($name);
		}
	
	
	
		/**
		 * Creates a new File with Content
		 *
		 * @param string $filename 
		 * @param string $content 
		 * @return fsFile
		 * @author Christian Blaschke
		 */
		public function createFile($filename, $content=null) {
			
			$file = $this->getPathname().DIRECTORY_SEPARATOR.$filename;
			
			file_put_contents($file, $content);
			return $this->find($filename);
		}
		
		
		
		/**
		 * Creates a new Dir
		 *
		 * @param string $filename 
		 * @return fsDir
		 * @author Christian Blaschke
		 */
		public function createDir($filename) {
			$dirpath = $this->getPathname().DIRECTORY_SEPARATOR.$filename;
			
			mkdir($dirpath, 0777, true);
			return $this->find($filename);
		}
		
		
		
		
	}

?>