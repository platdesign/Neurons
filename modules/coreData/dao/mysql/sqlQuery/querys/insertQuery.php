<?PHP
namespace sqlQuery;

class insertQuery {
	
	use dataObject;
	use table;
	
	public function __construct() {
		$this->object(new \stdClass);
	}
	
	
	public function __tostring() {
		$keys = array_keys((array) $this->object);
		
		$query = 'INSERT INTO `'.$this->table.'` 
			(`'.implode('`, `', $keys).'`) 
		VALUES 
			(:'.implode(', :', $keys).')';
		return $query;
	}
}

?>