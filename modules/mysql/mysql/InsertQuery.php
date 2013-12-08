<?PHP
namespace mysql;

	class InsertQuery extends Query {
	
		public function resultHandler($stmt, $pdo) {
			if($stmt->rowCount()==1){
				$id = $pdo->lastInsertId();
			
				$this->trigger("done", $id);
				return $id;
			}
		}
	
	}
	

?>