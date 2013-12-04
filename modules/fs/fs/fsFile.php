<?PHP
namespace nrns;
use nrns;

class fsFile extends \splFileInfo {
	
	
	use fsItem;
	
	
	
	public function __construct($path) {
		parent::__construct($path);
		
		return $this;
	}
	

	/**
	 * Returns the Mime-Type of the File
	 *
	 * @return string
	 * @author Christian Blaschke
	 */
	public function cached_getMime() {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		return finfo_file($finfo, (string)$this);
	}
	
	
	
	/**
	 * Parses the file and returns the parsed data
	 *
	 * @param string $type 
	 * @return parsed data
	 * @author Christian Blaschke
	 */
	public function parseAs($type) {
		switch(strtolower($type)) {
			
			case "html":
				return file_get_contents($this);
			break;
			
			case "json":
				return json_decode(file_get_contents($this));
			break;
			
			case "md":
				require_once "libs/markdown.php";
				return Markdown(file_get_contents($this));
			break;
			
			
		}
	}
	
	
	public function get_contents() {
		return file_get_contents($this);
	}
	
	public function import() {
		require $this;
	}
	
	
	
	
}

?>