<?PHP

namespace html\tag;

	
	class head extends \html\tag {
	
		use ogSetter;
		
		public $name = 'head';
		
		private $base = null;
		private $meta = [];
		private $css = [];
		private $js = [];
		private $title;
	
		
		/**
		 * The constructor sets charset and viewport to its' stdValues.
		 *
		 * @author Christian Blaschke
		 */
		public function __construct() {
			$this->setCharset();
			$this->setViewport();
		}
	
	
		/**
		 * Sets a base-url
		 *
		 * @param string $base 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function setBase($base) {
			$this->base = $base;
			return $this;
		}
	
	
		/**
		 * Sets a meta-tag-config
		 *
		 * @param string $key 
		 * @param string $vals 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function setMeta($key, $vals=[]) {
			$this->meta[$key] = $vals;
			return $this;
		}
	
	
		/**
		 * Sets the value for the title-tag
		 *
		 * @param string $title 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function setTitle($title) {
			$this->title = $title;
			return $this;
		}
	
	
		/**
		 * Sets a meta-tag with author-information
		 *
		 * @param string $author 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function setAuthor($author) {
			if( is_array($author) ) {
				$author = implode(',',$author);
			}
			$this->setMeta('author', ['name'=>'author', 'content'=>$author]);
			return $this;
		}
	
	
		/**
		 * Sets a meta-tag with description-information
		 *
		 * @param string $desc 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function setDescription($desc) {
			$this->setMeta("description", ["name"=>"description", "content"=>$desc]);
			return $this;
		}
	
	
		/**
		 * Sets a meta-tag with keyword(s)
		 *
		 * @param string $keywords 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function setKeywords($keywords) {
			if(is_array($keywords)) {
				$keywords = implode(",",$keywords);
			}
			
			$this->setMeta("keywords", ["name"=>"keywords", "content"=>$keywords]);
			return $this;
		}
	
	
		/**
		 * Adds a css-resource
		 *
		 * @param string $resource 
		 * @return void
		 * @author Christian Blaschke
		 */
		public function css($resource) {
			$this->css[] = $resource;
			return $this;
		}
	

		/**
		 * Adds a javascript-resource
		 *
		 * @param string $resource 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function js($resource) {
			$this->js[] = $resource;
			return $this;
		}
	

		/**
		 * Sets the Charset-meta-tag-information
		 *
		 * @param string $charset 
		 * @return this
		 * @author Christian Blaschke
		 */
		public function setCharset($charset='utf-8') {
			$this->setMeta('charset', ['http-equiv'=>'Content-type', 'content'=>'text/html; charset='.$charset]);
			return $this;
		}
	

		/**
		 * Sets a meta-tag for redirecting to an url
		 *
		 * @param string $url 
		 * @param string $time in seconds
		 * @return this
		 * @author Christian Blaschke
		 */
		public function redirect($url, $time=5) {
			$this->setMeta('redirect', ['http-equiv'=>'refresh', 'content'=>$time.'; URL='.$url]);
			return $this;
		}
	
	
		/**
		 * Sets a meta-tag with viewport-information
		 *
		 * @param string $url 
		 * @param string $time in seconds
		 * @return this
		 * @author Christian Blaschke
		 */
		public function setViewport($content='width=device-width, initial-scale=1') {
			$this->setMeta('viewport', ['name'=>'viewport', 'content'=>$content]);
		}
	

		/**
		 * Renders the Head-Tag and sets the content
		 *
		 * base-tag, meta-tags, title-tag, js-resoureces, css-resources
		 *
		 * @return string
		 * @author Christian Blaschke
		 */
		public function __tostring() {
			
			/* Prepend Content with base if is set */
			if( $this->base ) {
				$this->prepend( \html::base($this->base) );
			}
			
			/* Append Content with meta-tags */
			foreach(array_values($this->meta) as $meta) {
				$this->append( \html::meta($meta) );
			}
			
			/* Append Content with Title if is set */
			if( $this->title ) {
				$this->append( \html::title($this->title) );
			}
			
			/* Append Content with js-scripts */
			foreach(array_unique($this->js) as $url) {
				$this->append( \html::js($url) );
			}
			
			/* Append Content with css-scripts */
			foreach(array_unique($this->css) as $url) {
				$this->append( \html::css($url) );
			}
			
			
			/* Return string of tag */
			return (string) parent::__tostring();
		}
	
	}






	trait ogSetter {
		
		private function setOg($key, $value) {
			$property = 'og:'.$key;
			$this->setMeta($property, ["property"=>$property, "content"=>$value]);
			return $this;
		}
		
		public function setOgTitle($val){
			return $this->setOg("title", $val);
		}
	
		public function setOgLocality($val){
			return $this->setOg("locality", $val);
		}
	
		public function setOgCountryName($val){
			return $this->setOg("country-name", $val);
		}
	
		public function setOgLatitude($val){
			return $this->setOg("latitude", $val);
		}
	
		public function setOgLongitude($val){
			return $this->setOg("longitude", $val);
		}
	
		public function setOgType($val){
			return $this->setOg("type", $val);
		}
	
		public function setOgUrl($val){
			return $this->setOg("url", $val);
		}
	
		public function setOgSiteName($val){
			return $this->setOg("site_name", $val);
		}
	
		public function setOgAdmins($val){
			return $this->setOg("admins", $val);
		}
	
		public function setOgPageID($val){
			return $this->setOg("page_id", $val);
		}
	
		public function setOgImage($val){
			return $this->setOg("image", $val);
		}
		
	}

?>