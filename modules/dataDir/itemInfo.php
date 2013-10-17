<?PHP
	namespace dataDir;
	use nrns;

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

?>