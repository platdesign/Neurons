<?PHP

	namespace coreData;
	use nrns;

	require "mysql/sqlQuery/sqlQuery.php";
	require "mysql/sqlEntity/sqlEntity.php";
	


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
				throw new \PDOException("PDO-Object not found");
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
		
		
		public function createEntity($classname = '\sqlEntity') {
			$ntt = new $classname;
			$ntt->_setDB($this);
			return $ntt;
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
		
		public function createEntity($classname = '\sqlEntity') {
			$ntt = new $classname;
			$ntt->_setDB($this->dao);
			$ntt->_setTable($this->name);
			return $ntt;
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
		
		
		
		private function sanitizeObject($object) {
			$sql = 'SHOW COLUMNS FROM '.$this->name.';';
			$fields = $this->dao->selectQuery($sql);
			$fieldsArray = [];
			foreach($fields as $field) {
				$fieldsArray[] = $field->Field;
			}

			$sanitized = new \stdClass;
			foreach($fieldsArray as $key) {
				if(isset($object->{$key})) {
					$sanitized->{$key} = $object->{$key};
				}
			}
			
			
			return $sanitized;
		}
		
		
		
		
		
		public function get($primary, $classname="stdClass") {
			$query ='SELECT * FROM `'.$this->name.'` WHERE `'.$this->getPrimaryName().'` = :primary;';
			$binds["primary"] = $primary;
			
			if($result = $this->selectQuery($query, $binds, $classname) ) {
				return $result[0];
			}
		}
		
		
		public function add($object) {
			$sanitized = (array)$this->sanitizeObject($object);

			$sql = 'INSERT INTO `'.$this->name.'` 
				(`'.implode("`,`", array_keys($sanitized)).'`)
				VALUES
				(:'.implode(",:", array_keys($sanitized)).')';
				
			
				$id = $this->dao->insertQuery($sql, $sanitized);
				return $this->get($id);
			
		}
		
		
		public function update($id, $object) {
			$sanitized = (array)$this->sanitizeObject($object);
			
			$sql = 'UPDATE `'.$this->name.'` SET ';
			foreach($sanitized as $key => $val) {
				$sql .= '`'.$key.'` = :'.$key.', ';
			}
			$sql = substr($sql, 0, -2).' WHERE `id` = :id;';
			
			$this->dao->updateQuery($sql, array_merge($sanitized, ["id"=>$id]));
			return $this->get($id);
		}
		
		
		public function save($object) {
			if(isset($object->id)) {
				return $this->update($object->id, $object);
			} else {
				return $this->add($object);
			}
		}
		
	}
?>