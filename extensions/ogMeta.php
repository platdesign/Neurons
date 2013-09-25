<?PHP
class ogMeta extends nrns\extension {

	private $doc;
	
	public function __construct($outputProvider) {
		$outputProvider->extendService(get_class($this), $this);
		
		$this->doc = $outputProvider->getService();
	}
	
	private function setMeta($key, $val) {
		if(!empty($val)) {
			$this->doc->setMeta("og:".$key, ["property"=>"og:".$key, "content"=>$val]);
		}
	}
	
	public function setTitle($val){
		$this->setMeta("title", $val);
	}
	
	public function setLocality($val){
		$this->setMeta("locality", $val);
	}
	
	public function setCountryName($val){
		$this->setMeta("country-name", $val);
	}
	
	public function setLatitude($val){
		$this->setMeta("latitude", $val);
	}
	
	public function setLongitude($val){
		$this->setMeta("longitude", $val);
	}
	
	public function setType($val){
		$this->setMeta("type", $val);
	}
	
	public function setUrl($val){
		$this->setMeta("url", $val);
	}
	
	public function setSiteName($val){
		$this->setMeta("site_name", $val);
	}
	
	public function setAdmins($val){
		$this->setMeta("admins", $val);
	}
	
	public function setPageID($val){
		$this->setMeta("page_id", $val);
	}
	
	public function setImage($val){
		$this->setMeta("image", $val);
	}
	
	
}
?>