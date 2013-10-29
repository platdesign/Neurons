<?PHP


require "html/tag.php";
require "html/tag/html.php";
require "html/tag/head.php";
require "html/tag/body.php";


	class html {
	
	/* Basics */

		private static function tag($name) {
			$tag = new html\tag();
			$tag->setName($name);
			return $tag;
		}
		
		private static function singleTag($name) {
			$tag = self::tag($name);
			$tag->isSingle(true);
			return $tag;
		}
	
	
	
	
	
	/* Special-Tags */
		
		public static function head() {
			return new html\tag\head();
		}
		
		public static function body() {
			return new html\tag\body();
		}
		
		public static function doc() {
			return new html\tag\html();
		}
		
		
		
		
		
		
	/* Tags */
		
		public static function base($href) {
			return self::singleTag('base')->setAttr('href', $href);
		}

		public static function meta($attrs=[]) {
			return self::singleTag('meta')->setAttrs($attrs);
		}

		public static function title($title) {
			return self::tag('title')->setContent($title);
		}

		public static function link($attrs=[]) {
			return self::singleTag("link")->setAttrs($attrs);
		}
	
		public static function script($attrs=[], $content=null) {
			return self::tag("script")->setAttrs($attrs)->setContent($content);
		}

		public static function h1($content) {
			return self::tag('h1')->setContent($content);
		}
		
		public static function h2($content) {
			return self::tag('h2')->setContent($content);
		}
		
		public static function h3($content) {
			return self::tag('h3')->setContent($content);
		}
		
		public static function h4($content) {
			return self::tag('h4')->setContent($content);
		}
	
		public static function b($content) {
			return self::tag('b')->setContent($content);
		}
	
		public static function u($content) {
			return self::tag('u')->setContent($content);
		}
		
		public static function i($content) {
			return self::tag('i')->setContent($content);
		}
	





		/* Helper */
		public static function css($href, $media='screen', $title='no title') {
			$attrs = [ 'rel' => 'stylesheet', 'href'=>$href, 'type'=>'text/css', 'media'=>$media, 'title'=>$title, 'charset'=>'utf-8'];
			return self::link($attrs);
		}

		public static function js($src) {
			$attrs = ['src'=>$src, 'type'=>'text/javascript', 'charset'=>'utf-8'];
			return self::script($attrs);
		}

		
		
		
		
		
		
		
	
	}
	




?>