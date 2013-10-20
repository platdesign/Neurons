<?PHP



	namespace router;



	trait routeSetter {
		
		public function when($route, $then) {
			$this->addRoute("ALL", $route, $then);
			return $this;
		}
		
		public function get($route, $then) {
			$this->addRoute("GET", $route, $then);
			return $this;
		}
		
		public function post($route, $then) {
			$this->addRoute("POST", $route, $then);
			return $this;
		}
		
		public function put($route, $then) {
			$this->addRoute("PUT", $route, $then);
			return $this;
		}
		
		public function delete($route, $then) {
			$this->addRoute("DELETE", $route, $then);
			return $this;
		}
		
	}


?>