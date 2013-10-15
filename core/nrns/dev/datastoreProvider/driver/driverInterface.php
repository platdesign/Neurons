<?PHP 

	namespace nrns\provider\datastoreProvider\driver;


	interface driverInterface {
	
	
		public function connect($options=[]);
	
		public function connection();
		
		public function close();
	
	
	}

	

?>