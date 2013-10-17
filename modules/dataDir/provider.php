<?PHP 

	namespace dataDir;
	use nrns;
	
	
	
	class dataDirProvider extends nrns\provider\provider {

		
		
		public function __invoke(){
			return \nrns::closure(function($dirname, $extensions){
				return new dataDirectory($dirname, $extensions);
			});
		}
		
		
	}
	

?>