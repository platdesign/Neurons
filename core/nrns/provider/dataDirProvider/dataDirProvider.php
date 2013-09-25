<?PHP namespace nrns\provider {
	use nrns;
	
	require "dataDirectory.php";
	
	class dataDirProvider extends provider {

		
		
		public function __invoke(){
			return \nrns::closure(function($dirname, $extensions){
				return new \nrns\dataDirectory($dirname, $extensions);
			});
		}
		
		
	}
	
}
?>