<?PHP

	namespace coreData;
	use nrns;
	
	require_once "dao/mysqlDao.php";

	class coreDataProvider extends nrns\provider\provider {
		
		
		public function __construct($injection) {
			$this->injection = $injection;
		}
		
		// DEPRECATED
		public function create($key, $type, $options=[]) {
			
			switch($type) {
				case 'mysql':
				
					$pdo = new \PDO("mysql:host=".$options['host'].";dbname=".$options['db'].";charset=utf8",$options['user'],$options['secret']);
					$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
					$dao = new mysqlDao($pdo);
					
					$this->injection->provideService($key."PDO", function()use($pdo){
						return $pdo;
					});
					
					$this->injection->provideService($key, function()use($dao){
						return $dao;
					});
					
				
				break;
			}
			
		}
		
		
		public function createMysqlDao($options) {
			
			$stdOptions = [
				"host"	=>	"localhost",
				"user"	=>	"",
				"secret"=>	"",
				"db"	=>	""
			];
			
			$options = array_merge($stdOptions, $options);
				
			$pdo = new \PDO("mysql:host=".$options['host'].";dbname=".$options['db'].";charset=utf8",$options['user'],$options['secret']);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
			return new mysqlDao($pdo);
			
		}
		
	}
?>