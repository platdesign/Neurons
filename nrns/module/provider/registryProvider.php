<?PHP 

namespace nrns;
use nrns;
	
	
	
	class registryProvider extends nrns\Provider {
		
		private $dirname = 'registry';

		public function __construct($nrns, $fs, $injection) {

			$this->fs = $fs;
			$this->injection = $injection;
		}	
		
		public function setDirName($name) {
			
			$this->dirname = $name;
		}

		public function getService() {
			return function($key){
				$path = $this->fs->createDir( $this->dirname )->createDir($key);

				return $this->injection->invoke('nrns\\registryProviderService', ['_path'=>$path]);

				
			};
		}
		
    
	}
	
	
	class registryProviderService {
		public function __construct($_path) {
			$this->path = $_path;
		}

		public function get($key) {
			if( $file = $this->path->find($key) ) {
				return $file->parseAs('json');
			}
		}

		public function set($key, $content) {
			if( $file = $this->path->find($key) ) {
				return $file->set_contents(json_encode($content));
			} else {
				return $this->path->createFile($key, json_encode($content));
			}
		}


	}
?>