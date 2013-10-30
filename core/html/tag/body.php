<?PHP

namespace html\tag;

	
	class body extends \html\tag {
	
		public $name = 'body';
		public $js = [];
		
		
		/**
		 * Alias for parent::setContent
		 *
		 * @param string $html 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function html($html) {
			$this->setContent($html);
			return $this;
		}
	
	
		/**
		 * Adds a js-resource which will be added at the end of the content
		 *
		 * @param string $url 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function js($url) {
			$this->js[] = $url;
			return $this;
		}
		
		
		/**
		 * Renders the body-tag with content
		 *
		 * js-resources
		 * 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function __tostring() {
			
			/* Append JS-Scripts to Content */
			foreach(array_unique($this->js) as $js) {
				$this->append( html::js($js) );
			}
			
			return parent::__tostring();
		}
	}

?>