<?PHP
	namespace nrns\module\uiRouter;
	use nrns;


	class segment {
		private $name, $options=[], $parent, $__last;
		public $children;
	
		public function __construct($injection) {
			$this->injection = $injection;
			$this->__last = $this;
		}
	
		public function setName($name) {
			$this->name = $name;
			return $this;
		}
	
		public function setOptions($options) {
			$this->options = $options;
			return $this;
		}
	
		public function segment($name, $options) {
			$s = $this->__last = $this->children->{$name} = $this->injection->invokeClass(SEGMENT);
			$s->setName($name)->setOptions($options);
			$s->parent = $this;
			return $this;
		}
	
		public function getOptions() {
			return $this->options;
		}
	
		public function getName() {
			return $this->name;
		}
	
		public function child($name) {
			return $this->children->{$name};
		}
	
		public function parent() {
			return $this->parent;
		}
	
		public function within($name=null) {
			if($name) {
				return $this->child($name);
			} else {
				return $this->__last;
			}
		}
	
		public function up() {
			return $this->parent();
		}
	
		public function getView($scope) {
			$ctrl = $this->injection->invokeClass("nrns\\controller", $scope);
			$view = $this->injection->invokeClass("nrns\\view", $scope);
		
			if($this->options['templateUrl']) { $view->setTemplateUrl($this->options['templateUrl']); }
			if($this->options['controllerUrl']) { $ctrl->setFile( $this->options['controllerUrl'] ); }
			if($this->options['controller']) { $ctrl->setClosure( $this->options['controller'] ); }
		
			$ctrl->call();
		
			return $view;
		}
		
		public function getChain($chain=null) {
			
			if($parent = $this->parent()) {
				$chain = $this->getName().".".$chain;
				$chain = $parent->getChain($chain);
			} else {
				$chain .= ".".$this->getName();
			}
			return rtrim($chain, ".");
			
		}
	}
?>