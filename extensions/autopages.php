<?PHP
class autopages extends nrns\extension {

	public function __construct($routeProvider, $app) {
		$routeProvider->extend(get_class($this), $this);
		
		$this->routeProvider = $routeProvider;
		$this->app = $app;
	}
	
	
	public function create($route, $config) {
		
		if(isset($config['dir'])) {
			
			$then = [];
			$then['extend'] = $this->getSubRoutes($this->app->__rootpath."/".$config['dir']);
			
			$homeRoute = isset($config['startAt'])?$config['startAt']:key($then['extend']);
			
			$then['templateUrl'] = $config['dir']."/index.php";
			
			
			
			$this->routeProvider->when($route, $then);
			$this->routeProvider->when($route, ['redirect'=>$route.$homeRoute]);
			
			
		} else {
			throw new Exception("PagesProvider: You have to define a 'dir' for the pages!");
		}
		
	}
	
	private function getSubRoutes($dir) {
		
		$subroutes = [];
		if( is_dir($dir) ) {
			foreach (new \DirectoryIterator($dir) as $file)
			{
			    if($file->isDot()) continue;

			    if($file->isDir()) {
					$subroute = [];
					
					
					if( !substr($file->getFilename(), 0, 5) != "data." ) {
						
						if( file_exists($file->getPathname()."/template.php") ) {
							$subroute["templateUrl"] = $file->getPathname()."/template.php";
						}
					
						if( file_exists($file->getPathname()."/controller.php") ) {
							$subroute["controllerUrl"] = $file->getPathname()."/controller.php";
						}
					
						if( $subs = $this->getSubRoutes($file->getPathname()) ) {
							$subroute['extend'] = $subs;
						}
					
						$subroutes["/".str_replace("__",":", $file->getFilename())] = $subroute;
					
					}
					
					
		        
			    }
			}
		
			return $subroutes;
		}
		
		
	}
	
}
?>