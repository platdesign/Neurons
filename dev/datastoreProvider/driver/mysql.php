<?PHP 

	namespace nrns\provider\datastoreProvider\driver;


	class mysql implements driverInterface {
		
		private $connection;
	
		public function connect($opts=[]) {
			
			extract($opts);
			
			
			$this->connection = new \PDO("mysql:host=".$host.";dbname=".$db.";charset=utf8",$user,$secret);
			
		}
	
		public function connection() {
			return $this->connection;
		}
	
		public function close() {
			
		}
	}

	

?>