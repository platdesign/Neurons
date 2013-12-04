<?PHP

class sqlEntity {
	protected $db;
	protected $table;


	public function _setDB($db) {
		$this->db = $db;
	}

	
	public function _setTable($table) {
		$this->table = $table;
	}

	
	


	public function __set($key, $val) {
	
		$setterMethod = 'set'.$key;
	
		if( method_exists($this, $setterMethod) ) {
			call_user_func_array([$this, $setterMethod], [$val]);
		}
		return $this;
	}

	public function setFromObj($obj) {
		foreach($obj as $key => $val) {
			$this->{$key} = $val;
		}
		return $this;
	}





	public function setId($val) {
		$this->id = $val;
	}

	public function setCreateTS($val) {
		$this->createTS = $val;
	}
	
	public function setUpdateTS($val) {
		$this->updateTS = $val;
	}

	public function _setCreateTS() {
		$this->createTS = date('c');
	}
	
	public function _setUpdateTS() {
		$this->updateTS = date('c');
	}
	


	public function getAllWhere($where, $binds=[]) {
		$query = sqlQuery::select()->where($where)->from($this->table);
		return $this->db->selectQuery($query, $binds, get_class($this));
	}

	public function getAllByUid($uid) {
		return $this->getAllWhere('`uid` = :uid',[':uid' => $uid] );
	}




	public function loadWhere($where, $binds=[]) {
		$query = sqlQuery::select()->where($where)->from($this->table);
		$result = $this->db->selectQuery($query, $binds);
		
		if($result) {
			$this->setFromObj($result[0]);
			return $this;
		}
	}

	public function loadById($id) {
		return $this->loadWhere('`id` = :id', [':id'=>$id]);
	}
	



	public function save() {
	
		if(isset($this->id)) {
			return $this->update();
		} else {
			return $this->create();
		}
	
	}

	public function create() {
	
		$this->_setCreateTS();
		$this->_setUpdateTS();
	
		$query = sqlQuery::insertInto($this->table)
			->object($this);
	
		$id = $this->db->insertQuery($query, $query->getBinds());
		if($id) {
			$this->setId($id);
			return $id;
		}
	
	}

	public function delete() {
	
		$query = sqlQuery::delete($this->table)
			->where('`id` = :id');
		
	
		$result = $this->db->deleteQuery($query, [
			"id"	=>	$this->id
		]);
	
		return $result;
	}

	public function update() {
		$this->_setUpdateTS();
		
		$query = sqlQuery::update($this->table)
			->object($this)
			->where('`id` = :id');
	
		$result = $this->db->updateQuery($query, $query->getBinds());
		return $result;
	}	
}

?>