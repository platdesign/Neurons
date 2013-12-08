<?PHP
namespace mysql;

	class SelectQuery extends Query {
		protected $classname;
		public function __construct($sql, $binds=[], $classname="stdClass") {
			$this->sql = $sql;
			$this->binds = $binds;
			$this->classname = $classname;
		}
	
		public function resultHandler($result, $pdo) {
			return $result->fetchAll(\PDO::FETCH_CLASS, $this->classname);
		}
	}
	

?>