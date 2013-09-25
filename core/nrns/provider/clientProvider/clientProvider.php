<?PHP namespace nrns\provider {
	use nrns;
	
	require "client.php";
	
	class clientProvider extends instanceProvider {
		
		function __construct() {
			$this->setInstance( new \client() );
		}
		
	}
	
}	

?>