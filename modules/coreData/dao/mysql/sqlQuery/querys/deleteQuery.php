<?PHP
namespace sqlQuery;

class deleteQuery {
	
	use where;
	use table;
	
	public function __tostring() {
		
		$query = 'DELETE FROM `'.$this->table.'` ';
		
		$query .= $this->where__tosring();
		
		return $query;
	}
	
}

?>