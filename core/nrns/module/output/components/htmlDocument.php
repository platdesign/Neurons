<?PHP
namespace nrns\module\output\components;

	class htmlDocument extends outputDocument {
		private $head;
		private $body;
		
		private $doctype = "html";

		public function __construct($app, $request) {  
			$this->app = $app;
			$this->__setStructur();
			
			$this->setBase($request->base);
		}
		
		private function __setStructur() {
			$this->head = new \stdClass;
			$this->head->assets = ["CSS"=>[], "JS"=>[]];
			$this->head->meta = [];
		}
		
		public function setBase($base) {
			$this->head->base = $base;
		}
		
		public function setMeta($key, $val) {
			$this->head->meta[$key] = $val;
		}
		
		public function setTitle($title) {
			$this->head->title = $title;
		}
		
		public function setAuthor($author) {
			$this->setMeta("author", ["name"=>"author", "content"=>$author]);
		}
		
		public function setDescription($desc) {
			$this->setMeta("description", ["name"=>"description", "content"=>$desc]);
		}
		
		public function setFavicon($url) {
			$this->favicon = $url;
		}
		
		public function addAsset($type, $resource) {
			$this->head->assets[$type][] = $resource;
		}
		
		public function addCssResource($url) {
			$this->addAsset("CSS", $url);
		}
		
		public function addJsResource($url) {
			$this->addAsset("JS", $url);
		}
		
		
		
		
		
		
		public function setBody($content) {
			$this->body = $content;
		}
		
		public function appendBody($content) {
			$this->body .= $content;
		}
		
		public function prependBody($content) {
			$this->body = $content.$this->body;
		}
		
		
		
		
		
		
		private function renderAssets() {
			$assets = "";
			
			foreach($this->head->assets['JS'] as $resource) {
				$assets .= '<script src="'.$resource.'" type="text/javascript" charset="utf-8"></script>';
			}
			
			foreach($this->head->assets['CSS'] as $resource) {
				$assets .= '<link rel="stylesheet" href="'.$resource.'" type="text/css" media="screen" title="no title" charset="utf-8">';
			}
			
			return $assets;
		}
		
		
		
		
		private function renderBase() {
			if( isset($this->head->base) ) {
				return '<base href="'.$this->head->base.'/">';
			}
		}
		
		
		private function renderTitle() {
			if( isset($this->head->title) ) {
				return '<title>'.$this->head->title.'</title>';
			} else {
				return '<title>New Neurons App</title>';
			}
		}
		
		private function renderMeta() {
			$meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';

			$as = function($attrs=[]) {
				$return = "";
				foreach($attrs as $key => $val) {
					$return .= $key.'="'.$val.'" ';
				}
				return $return;
			};
			
			foreach($this->head->meta as $attrs) {
				$meta .= '<meta '.$as($attrs).'>';
			}
			return $meta;
		}
		
		private function renderFavicon() {
			if( isset($this->favicon) ) {
				return '<link rel="icon" href="'.$this->favicon.'" type="image/ico">';
			}
		}
		
		private function renderHead() {
			
			$head = "";
			$head .= $this->renderBase();
			$head .= $this->renderMeta();
			$head .= $this->renderTitle();
			$head .= $this->renderFavicon();
			
			$head .= $this->renderAssets();
			return $head;
		}
		
		private function renderBody() {
			return (string) $this->body;
		}
		
		public function render() {
			
			$body = $this->renderBody();
			$head = $this->renderHead();
			
			$html = '<!DOCTYPE '.$this->doctype.'><html><head>'.$head.'</head><body>'.$body.'</body></html>';
			
			return $html;
		}
		

	}
?>