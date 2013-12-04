<?PHP
namespace sqlQuery;

class selectQuery {
	
	use where;
	use table;
	
	public function setCols($cols) {
		$this->cols = $cols;
		return $this;
	}
	
	
	public function from($table) {
		return $this->setTable($table);
	}
	
	
	public function __tostring() {
		
		$query = 'SELECT * FROM `'.$this->table.'` ';
		
		$query .= $this->where__tosring();
		
		return $query;
	}
	
}

?>