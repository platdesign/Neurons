<?PHP namespace nrns;


	class keyValStore {
	
		private $store = [];
	
		public function set($key, $val) {
			$this->store[$key] = $val;
		}
	
		public function get($key) {
			return (isset($this->store[$key])) ? $this->store[$key] : null;
		}
		
		public function getAllKeys() {
			return array_keys($this->store);
		}
	
	}
?>