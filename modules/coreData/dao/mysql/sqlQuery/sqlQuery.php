<?PHP

require "traits.php";
require "querys/insertQuery.php";
require "querys/selectQuery.php";
require "querys/deleteQuery.php";
require "querys/updateQuery.php";


	class sqlQuery {
	
		public static function insertInto($table) {
			$query = new \sqlQuery\insertQuery();
			$query->setTable($table);
			return $query;
		}
	
		public static function select($cols="*") {
			$query = new \sqlQuery\selectQuery();
			$query->setCols($cols);
			return $query;
		}

		public static function update($table) {
			$query = new \sqlQuery\updateQuery();
			$query->setTable($table);
			return $query;
		}
	
		public static function delete($table) {
			$query = new \sqlQuery\deleteQuery();
			$query->setTable($table);
			return $query;
		}
	
	}












?>