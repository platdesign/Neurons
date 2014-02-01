<?php 
namespace nrns;

class mysqlPDO extends \PDO {

	public function __construct($db, $user, $secret='', $host='localhost') {
		try {
			parent::__construct("mysql:host=$host;dbname=$db;charset=utf8",$user,$secret);
			$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch(\Exception $e) {
			throw $e;
		}
		
		$this->info = (object)[
			'db_name'	=>	$db
		];
	}
	
}

?>