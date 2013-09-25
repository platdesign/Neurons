<?PHP namespace nrns\provider;
use nrns;


	class pdoProvider extends provider {
		private $connections = [];
	
		public function __construct() {
			
			$this->cons = new nrns\keyValStore();
		}
		
		public function createConnection($key, $host, $db, $user="root", $secret="") {
			$this->cons->set($key, new \PDO("mysql:host=".$host.";dbname=".$db.";charset=utf8",$user,$secret));
		}
	
		public function connection($key) {
			return $this->cons->get($key);
		}
		
		public function __invoke() {
			
			return nrns::closure(function($key){
				
				return $this->connection($key);
			}, $this);
			
			
			
			
		}
	}
	
	

?>