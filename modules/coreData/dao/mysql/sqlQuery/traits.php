<?PHP

namespace sqlQuery;


trait dataObject {
	
	private $object = [];
	
	public function object($obj) {
		
		
		
		$this->object = get_object_vars($obj);
		return $this;
	}
	
	public function getBinds() {
		$binds = [];
		
		foreach($this->object as $key => $val) {
			$binds[':'.$key] = $val;
		}
		return $binds;
	}
}


trait where {
	private $where = [];
	
	public function where($where) {
		$this->where[] = $where;
		return $this;
	}
	
	private function where__tosring() {
		if( count($this->where) > 0 ) {
			$query = "WHERE ";
			
			$query .= implode(" AND ", $this->where);
			
			return $query;
		} else {
			return;
		}
	}
}


trait table {
	private $table;
	
	public function setTable($table) {
		$this->table = $table;
		return $this;
	}
}





?>