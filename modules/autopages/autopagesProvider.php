<?PHP 
	
	namespace nrns\module\autopages;
	use nrns;
	

	class autopagesProvider extends nrns\provider\provider {
		
		
		public function __construct($injection, $segmentProvider, $nrns, $fs) {
			$this->injection = $injection;
			$this->segmentProvider = $segmentProvider;
			$this->nrns = $nrns;
			$this->fs = $fs;
			
		}
		
		public function scanDir($path) {
			
			
			if( $dir = $this->fs->find($path) ) {
				
				$segment = $this->segmentProvider->segment($dir->getName(), []);
				
				$this->registerDir("/", $dir, $segment);
				
			}
			
			
			
			
		}
		
		
		public function registerDir($route, $dir, $parentSegment) {
			$options = [];
			
			
			if($route!="/") {
				$this->segmentProvider->when($route, $this->segmentProvider->getChainOfSegment($parentSegment));
			} else {
				$route = NULL;
			}
			
			
			if( $template = $dir->find("template.php") ) {
				$options["templateUrl"] = $template->getPathname();
			}
			if( $controller = $dir->find("controller.php") ) {
				$options["controllerUrl"] = $controller->getPathname();
			}

			
			$parentSegment->setOptions($options);
			
			foreach ($dir->dirs() as $file) {
				
					if( substr($file->getName(), 0, 1) != "!") {
					
					
						if( substr($file->getName(), 0, 2) == "__" ) {
							$routeExtender = "/:".substr($file->getName(), 2);
						} else {
							$routeExtender = "/".$file->getName();
						}
					
						
						$this->registerDir(
							$route.$routeExtender, 
							$file,
							$parentSegment->segment($file->getName(), [])
						);
					}
			    
				
			}
			
		}
		
	
	}

?>