<?PHP namespace nrns\provider;
use nrns;



	class daoProvider extends provider {
		private $daos = [];
		
		public function __construct() {
			$this->daos = new nrns\keyValStore();
		}
		
		public function createDao($table, $pdo) {
			$dao = new \dao($pdo, $table);
			
			$this->daos->set($table, $dao);
			return $dao;
		}
		
		public function getDao($table) {
			return $this->daos->get($table);
		}
	
		public function __invoke() {
		
			return new nrns\JSClosure(function($table){
				return $this->getDao($table);
			}, $this);
			
			
			
		}
	}
	
	

?>