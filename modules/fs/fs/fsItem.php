<?PHP
namespace nrns;
use nrns;

trait fsItem {
	
	use methodcache;
	use fsItemInfo;
	
	
	
	private $parent;
	
	
	/**
	 * Sets the parentDir
	 *
	 * @param string $parent 
	 * @return fsDir
	 * @author Christian Blaschke
	 */
	public function setParent($parent) {
		$this->parent = $parent;
		return $this;
	}
	
	
	
	/**
	 * Returns the parentDir
	 *
	 * @return fsDir
	 * @author Christian Blaschke
	 */
	public function parent(){
		return $this->parent;
	}

	
	
	/**
	 * Returns a sibling File | Dir
	 *
	 * @param string $name 
	 * @return fsDir | fsFile
	 * @author Christian Blaschke
	 */
	public function sibling($name) {
		return $this->parent()->find($name);
	}
	
	
	
	public function isWriteable() {
		return is_writable($this);
	}
	
}


?>