<?PHP
namespace mysql;

	class Query {
		use \nrns\events;
	
		protected $sql, $binds = [];
	
		public function __construct($sql, $binds=[]) {
			$this->sql = $sql;
			$this->binds = $binds;
		}
	
		private function prepare($pdo) {
			return $pdo->prepare($this->sql, $this->binds);
		}
	
		public function resultHandler($stmt, $pdo) {
		
		}
	
		public function exec($pdo) {
			try {
				$stmt = $this->prepare($pdo);
				$this->trigger("exec");
				$stmt->execute($this->binds);
				return $this->resultHandler($stmt, $pdo);
			}catch(\PDOException $e) {
				$this->trigger("fail", $e);
			}
		
		}
	
	
	}


?>