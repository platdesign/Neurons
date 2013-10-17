<?PHP

class dao {
	
	public $pdo;
	public $table;
	public $schema;
	public $idAttr;
	
	public function __construct($pdo, $table) {
		$this->table = $table;
		$this->pdo = $pdo;
	}

	/* QUERY FUNCTIONS */
	public function query($query, $binds=[]){
		if($this->pdo) {
			
			$stmt = $this->pdo->prepare($query);
			$stmt->execute($binds);
			return $stmt;
			
		} else {
			throw new Exception("PDO-Object not found");
		}
	}
	
	public function schema(){
		if(!$this->schema) {
			$schema = $this->selectQuery("SHOW COLUMNS FROM `".$this->table."`;");
			foreach($schema as $attr) {
				if($attr->Key == "PRI") { $this->idAttr = $attr->Field; }
			}
			$this->schema = $schema;
		}
		
		return $this->schema;
	}
	
	
	public function selectQuery($query, $binds=[], $classname="stdClass") {
		$stmt = $this->query($query, $binds);
		return $stmt->fetchAll(PDO::FETCH_CLASS, $classname);
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
	
	
	
	/* OBJECT FUNCTIONS */
	public function selectObject($id){
		$this->schema();
		
		$query = "SELECT * FROM `".$this->table."` WHERE `".$this->idAttr."` = :id LIMIT 1;";
		$result = $this->selectQuery($query, ["id"=>$id]);
		
		if($result){
			return $result[0];
		} else {
			throw new Exception("Object not found", 404);
		}
	}
	
	public function selectObjects(){
		$query = "SELECT * FROM `".$this->table."`;";
		return $this->selectQuery($query);
	}
	
	public function insertObject(&$object){

		$schemaObject = $this->schemarizeObject($object);
		$attrs = array_keys((array) $schemaObject);

		$query = "INSERT INTO `".$this->table."` (`".implode("`, `", $attrs)."`) VALUES (:".implode(", :", $attrs).");";
		$id = $this->insertQuery($query, (array) $schemaObject);
		
		if($id) {
			$object->{$this->idAttr} = $id;
			return $id;
		} else {
			throw new Exception("Object save error");
		}
		
	}
	
	public function updateObject($object){
		$schemaObject = $this->schemarizeObject($object);
		
		
		$query = "UPDATE `".$this->table."` SET ";
		$binds[$this->idAttr] = $this->findId($object);
		
		foreach($schemaObject as $key => $val){
			$query .= "`".$key."` = :".$key.", ";
			$binds[$key] = $val;
		}
		
		$query = substr($query, 0, -2)." WHERE `".$this->idAttr."` = :id LIMIT 1;";
		
		$result = $this->updateQuery($query, $binds);
		
		if($result) {
			return true;
		} else {
			throw new Exception("Object save error");
		}
	}
	
	public function deleteObject($modelOrID){
		$query = "DELETE FROM `".$this->table."` WHERE `".$this->idAttr."` = :id LIMIT 1;";

		$result = $this->deleteQuery($query, ["id"=>$this->findId($modelOrID)]);
		
		if($result){
			return true;
		} else {
			throw new Exception("Object not found");
		}
	}
	

	/* Helper to find the ID-value of a model OR an ID-Var */
	private function findID($arg){
		if(is_array($arg)) { 
			$id = $arg[$this->idAttr]; 
		} else if(is_object($arg)){ 
			$id = $arg->{$this->idAttr}; 
		} else if(is_string($arg) OR is_int($arg) OR is_numeric($arg)) { 
			$id = $arg; 
		} else {
			throw new Exception("Missing ID");
		}
		
		return $id;
	}
	
	private function schemarizeObject($object) {
		$schema = $this->schema();
		$return = new stdClass;
		
		foreach($schema as $attr){
			$name = $attr->Field;
			$value = $object->{$name};
			
			if($name != $this->idAttr){
				if($value === NULL AND $attr->Null == "NO") {
					throw new Exception("Missing Value: ". $name);
				} else {
					$return->{$name} = $value;
				}
			}
		}
		
		return $return;
	}
	
}
?>