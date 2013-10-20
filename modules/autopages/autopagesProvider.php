<?PHP 
	
	namespace nrns\module\autopages;
	use nrns;
	

	class autopagesProvider extends nrns\provider\provider {
		
		
		public function __construct($injection, $uiRouteProvider, $nrns, $fs) {
			$this->injection = $injection;
			$this->uiRouteProvider = $uiRouteProvider;
			$this->nrns = $nrns;
			$this->fs = $fs;
		}
		
		public function scanDir($dir, $startWith=null) {
			$dir = nrns::$rootpath.DIRECTORY_SEPARATOR.$dir;
			
			if( is_dir($dir) ) {
				
				$segment = $this->uiRouteProvider->segment(basename($dir), [])->within();
				
				$this->registerDir("/", $dir, $segment);
				
			}
			
			
			if($startWith) {
				$this->uiRouteProvider->otherwise(["redirect"=>$startWith]);
			}
			
		}
		
		
		public function registerDir($route, $dir, $parentSegment) {
			
			if($route!="/") {
				$this->uiRouteProvider->when($route, $parentSegment->getChain());
			} else {
				$route = NULL;
			}
			
			
			if( file_exists($dir."/template.php") ) {
				$options["templateUrl"] = $dir."/template.php";
			}
			if( file_exists($dir."/controller.php") ) {
				$options["controllerUrl"] = $dir."/controller.php";
			}
			
			$parentSegment->setOptions($options);
			
			foreach (new \DirectoryIterator($dir) as $file) {
			    if($file->isDot()) continue;

			    if($file->isDir()) {
					if( substr($file, 0, 1) != "!") {
					
					
						if( substr($file, 0, 2) == "__" ) {
							$routeExtender = "/:".substr($file, 2);
						} else {
							$routeExtender = "/".$file;
						}
					
					
						$this->registerDir(
							$route.$routeExtender, 
							$file->getPathname(), 
							$parentSegment->segment($file, [])->within()
						);
					}
			    }
				
			}
			
		}
		
	
	}

?>