<?PHP
namespace mysql;

	class SingleSelectQuery extends SelectQuery {
	
		public function resultHandler($result, $pdo) {
			return $result->fetchObject($this->classname);
		}
	}

?>