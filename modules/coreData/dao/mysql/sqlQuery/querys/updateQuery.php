<?PHP
namespace sqlQuery;

class updateQuery {
	
	use where;
	use table;
	use dataObject;
	
	
	
	private function updateSetter__tostring() {
		
		if($this->object) {
			$query = "SET ";
			
			foreach($this->object as $key => $val) {
				$query .= '`'.$key.'` = :'.$key.', ';
			}
			$query = substr($query, 0, -2).' ';
			return $query;
		}
		
		return $query;
		
	}
	
	public function __tostring() {
		$query = 'UPDATE `'.$this->table.'` ';
		
		$query .= $this->updateSetter__tostring();
		$query .= $this->where__tosring();
		
		return $query;
	}
	
}

?>