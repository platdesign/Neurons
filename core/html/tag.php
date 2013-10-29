<?PHP

namespace html;
	
	
	class tag {
		public $name = 'div';
		public $attrs = [];
		public $single = false;
		public $content = null;
		
		
		public function setAttrs($attrs=[]) {
			$this->attrs = array_merge($this->attrs, $attrs);
			return $this;
		}
		
		public function setAttr($key, $val) {
			$this->attrs[$key] = $val;
			return $this;
		}
		
		public function setName($name) {
			$this->name = $name;
			return $this;
		}
		
		public function setContent($content) {
			$this->content = $content;
			return $this;
		}
		
		public function isSingle($bool = false) {
			$this->single = $bool;
			return $this;
		}
		
		
		public function prepend($html) {
			$this->content = $html.$this->content;
			return $this;
		}
		
		public function append($html) {
			$this->content .= $html;
			return $this;
		}
		
		
		
		
		
		
		
		
		private function attrs() {
			$return = null;
		
			foreach($this->attrs as $key => $val) {
				$return .= $key.'="'.$val.'" ';
			}
			return substr(' '.$return, 0, -1);
		}
		
		private function open() {
			return '<'.$this->name.$this->attrs().'>';
		}
		
		private function close() {
			return '</'.$this->name.'>';
		}
		
		private function single() {
			return '<'.$this->name.$this->attrs().' />';
		}
		
		private function tag() {
			return $this->open().$this->content.$this->close();
		}
		
		public function __tostring() {
			if($this->single) {
				return (string) $this->single();
			} else {
				return (string) $this->tag();
			}
		}
		
	}

?>