<?PHP 

class disqus extends \nrns\plugin {

	public function __invoke($shortname) {
		$this->shortname = $shortname;
	}

	public function __tostring(){
		return nrns::loadTemplateContent(dirname(__FILE__)."/template.php", [
			"shortname"	=>	$this->shortname
		]);
	}
	
}

?>