<?PHP

	namespace coreData;
	use nrns;

	class mysqlDao {
		
		use nrns\methodcache;
		
		private $pdo;
		private $tables;
		
		public function __construct($pdo) {
			$this->pdo = $pdo;
		}
		
		/* QUERY FUNCTIONS */
		public function query($query, $binds=[]){
			if($this->pdo) {
			
				$stmt = $this->pdo->prepare($query);
				$stmt->execute($binds);
				return $stmt;
			
			} else {
				throw new \Exception("PDO-Object not found");
			}
		}
		
		public function selectQuery($query, $binds=[], $classname="stdClass") {
			$stmt = $this->query($query, $binds);
			return $stmt->fetchAll(\PDO::FETCH_CLASS, $classname);
		}
	
		public function deleteQuery($query, $binds=[]){
			$stmt = $this->query($query, $binds);
			return $stmt->rowCount();
		}
	
		public function updateQuery($query, $binds=[]){
			$stmt = $this->query($query, $binds);
			return $stmt->rowCount();
		}
	
		public function insertQuery($query, $binds=[]){
			$stmt = $this->query($query, $binds);
			if($stmt->rowCount()==1){
				return $this->pdo->lastInsertId();
			}
		}
		

		public function cached_table($name) {
			if( !isset($this->tables) ) { $this->tables = new \stdClass; }
			return $this->tables->{$name} = mysqlDaoTable::init($name, $this);
		}
		
		
		/**
		 * Alias for table()
		 *
		 * @param string $key 
		 * @return mysqlDaoTable
		 * @author Christian Blaschke
		 */
		public function __get($key) {
			
			if( isset($this->{$key}) ) {
				return $this->{$key};
			} else {
				return $this->table($key);
			}
			
		}
		
	}
	
	class mysqlDaoTable {
		
		use nrns\methodcache;
		
		private $name, $dao;
		
		public static function init($name, $dao) {
			$i = new mysqlDaoTable($name, $dao);
			return $i;
		}
		
		public function __construct($name, $dao) {
			$this->name = $name;
			$this->dao = $dao;
		}
		
		private function cached_getPrimaryName() {
			$query = "SHOW KEYS FROM `$this->name` WHERE Key_name = 'PRIMARY';";
			return $this->selectQuery($query)[0]->Column_name;
		}
		
		
		
		/* Decorate QUERY FUNCTIONS FROM DAO */
		public function query($query, $binds=[]){
			return $this->dao->query($query, $binds);
		}
		
		public function selectQuery($query, $binds=[], $classname="stdClass") {
			return $this->dao->selectQuery($query, $binds, $classname);
		}
	
		public function deleteQuery($query, $binds=[]){
			return $this->dao->deleteQuery($query, $binds);
		}
	
		public function updateQuery($query, $binds=[]){
			return $this->dao->updateQuery($query, $binds);
		}
	
		public function insertQuery($query, $binds=[]){
			return $this->dao->insertQuery($query, $binds);
		}
		
		
		
		
		
		
		
		public function getAll() {
			return $this->selectQuery("SELECT * FROM `$this->name`;");
		}
		
		public function get($primary) {
			$query ='SELECT * FROM `'.$this->name.'` WHERE `'.$this->getPrimaryName().'` = :primary;';
			$binds["primary"] = $primary;
			
			if($result = $this->selectQuery($query, $binds) ) {
				return $result[0];
			}
		}
		
		
		
	}
?>