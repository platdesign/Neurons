<?PHP
	namespace nrns;
	use nrns;
	
	class segment {

		private $children = [];
		private $parent;
		public $key;
		private $vals;
		
		public function __construct($key) {
			$this->key = $key;
		}
		
		public function createChild($key) {
			$classname = get_class($this);
			
			$child = new $classname($key);
			$child->setParent($this);
			
			return $this->children[$key] = $child;
		}
		
		public function child($key) {
			if( isset($this->children[$key]) ) {
				return $this->children[$key];
			}
		}
		
		public function setParent($parent) {
			$this->parent = $parent;
		}
		
		public function getKey() {
			return $this->key;
		}
		
		public function getChain() {
			if( isset($this->parent) ) {
				 
				$parent = $this->parent();
				$chain = null;
				while( isset($parent) ) {
					$chain = $parent->getKey().".".$chain;
					$parent = $parent->parent();
				}
				 
				return $chain.$this->key;
			} else {
				return $this->key;
			}
		}

		public function find($chain=null) {
			
			$keys = explode(".",$chain);
			
			$child = $this;
			
			
			foreach($keys as $key) {
				$newChild = $child->child($key);
				if($newChild) { $child = $newChild; } else { break; }
			}
			
			return $child;
			
		}
		
		public function parent() {
			if( isset($this->parent) ) {
				return $this->parent;
			}
		}
		
		public function rootNode() {
			
			if( $parent = $this->parent() ) {
				return $parent->rootNode();
			} else {
				return $this;
			}
			
		}
		
		public function __set($key, $val) {
			$this->vals[$key] = $val;
		}
		
		public function __get($key) {
			if( isset($this->vals[$key]) ) {
				return $this->vals[$key];
			}
		}
		
		public function getSegmentsFromRoot() {
			
			$chain = [];
			$segment = $this;
			while( $segment ) {
				
				$chain[] = $segment;
				
				if( $parent = $segment->parent() ) {
					$segment = $parent;
				} else {
					$segment = null;
				}
			}
			
			$chain = array_reverse($chain);
			
			return $chain;
		}
		
	}
?>