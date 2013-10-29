<?PHP

namespace html\tag;

	
	class html extends \html\tag {
	
		public $name = 'html';
		public $head, $body;
		
		
		/**
		 * Creates a head- and a body-tag-object
		 *
		 * @author Christian Blaschke
		 */
		public function __construct() {
			$this->head = \html::head();
			$this->body = \html::body();
		}
		
		
		/**
		 * Renders the html-tag with content
		 *
		 * doctype, html-tag ( head-tag, body-tag )
		 *
		 * @return void
		 * @author Christian Blaschke
		 */
		public function __tostring() {
			$this->setContent( (string) $this->body );
			$this->prepend( (string) $this->head );

			return '<!DOCTYPE html>' . parent::__tostring();
		}
	
	}

?>