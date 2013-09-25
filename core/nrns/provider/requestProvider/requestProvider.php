<?PHP namespace nrns\provider;
	
	use nrns;


	require "request.php";

	class requestProvider extends instanceProvider {
	
		function __construct() {
			$this->setInstance( new nrns\request() );
		}
	
	}
	


?>