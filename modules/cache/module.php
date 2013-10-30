<?PHP

	namespace nrns;
	use nrns;
	
	

	$module = nrns::module('cache', ['fs']);
	
	
	$module->provider('cacheProvider', __NAMESPACE__.'\cacheProvider');
	
	
	
	class cacheProvider extends nrns\provider\provider {
		
		private $duration = 3600;
		private $path;
		
		public function __construct($nrns, $routeProvider, $fs, $response) {
			$this->nrns = $nrns;
			$this->routeProvider = $routeProvider;
			$this->fs = $fs;
			$this->response = $response;
			
			$this->setPath('./cache');
		}
		
		public function route($route) {
			
			$this->nrns->on('after:config', function()use($route){
				
				$activeRoute = $this->routeProvider->getService();
				
				if( $activeRoute->request->getMethod() === 'GET' ) {
					
					if($activeRoute->_route === $route) {
						
						$cachefilename = md5($activeRoute->request->getRoute()).'.html';
						$this->cacheOutput($cachefilename);

					}
				}
				
				
			
			});
			
		}
		
		public function setDuration($duration) {
			$this->duration = $duration;
		}
		
		private function cacheDir() {
			return $this->fs->find($this->path);
		}
		
		public function setPath($path) {
			
			if( $cacheDir = $this->fs->find($path) ) {
				if( $cacheDir->isWriteable() ) {
					$this->path = $path;
				} else {
					die("CACHE DIR NOT WRITEABLE");
				}
			} else {
				
				if( !$this->fs->createDir($path) ) {
					die("CREATE CACHE DIR");
				} else {
					$this->path = $path;
				}
				
			}
			
		}
		
		private function cacheOutput($filename) {
			
			if($c_file = $this->cacheDir()->find($filename)) {
				
				if( $c_file->getCTime() >= (time()-$this->duration) ) {
					
					$this->nrns->clearEvent("run");
		
					$this->nrns->on("before:render", function()use($c_file){
						$this->response->setBody($c_file->parseAs('html'));
					});
				} else {
					
					$this->bufferOutputAndSave($filename);
					
				}
				
				
			} else {
				$this->bufferOutputAndSave($filename);
			}
			
			
		}
		
		
		private function bufferOutputAndSave($filename) {
			
			ob_start();
	
			$this->nrns->on("after:render", function()use($filename){
				$content = ob_get_contents();
				ob_end_flush();
			
				$this->cacheDir()->createFile($filename, $content);
			});
			
		}
		
	}

	
?>