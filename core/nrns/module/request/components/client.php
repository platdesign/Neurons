<?PHP
namespace nrns\module\request\components;

class client {
	
	private $browser;
	
	public function __construct() {
		//$this->detectBrowser();
	}
	
	public function detectBrowser() {
		if(!$this->browser) {
			$this->browser = get_browser();
		}
		return $this->browser;
	}
	
	public function browser() {
		return $this->browser->browser;
	}
	
	public function platform() {
		return $this->browser->platform;
	}
	
	public function browserVersion() {
		return $this->browser->version;
	}
	
	public function ip() {
		$ipaddress = '';
		
		$indices = ["HTTP_CLIENT_IP", "HTTP_X_FORWARDED_FOR", "HTTP_X_FORWARDED", "HTTP_FORWARDED_FOR", "HTTP_FORWARDED", "REMOTE_ADDR"];
		
		foreach($indices as $index) {
			if( isset( $_SERVER[$index] ) ) {
				return $_SERVER[$index];
			}
		}
	}
}

?>