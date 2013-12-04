<?PHP 

	namespace nrns;


	class PDO extends \PDO {
		
		public function __construct($user, $secret, $db, $host="localhost") {
			parent::__construct('mysql:host='.$host.';dbname='.$db, $user, $secret);
			$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		
	}
	
	
?>