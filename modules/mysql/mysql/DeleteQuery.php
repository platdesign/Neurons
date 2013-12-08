<?PHP
namespace mysql;

	class DeleteQuery extends Query {
	
		public function resultHandler($stmt, $pdo) {
			if($stmt->rowCount()==1){
				$this->trigger("done");
				return true;
			}
		}
	
	}

	

?>