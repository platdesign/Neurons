<?PHP

	namespace coreData;
	use nrns;

	class coreDataProvider extends nrns\provider\provider {
		
		
		public function __construct($injection) {
			$this->injection = $injection;
		}
		
		public function create($key, $type, $options=[]) {
			
			switch($type) {
				case 'mysql':
				require_once "dao/mysqlDao.php";
					$pdo = new \PDO("mysql:host=".$options['host'].";dbname=".$options['db'].";charset=utf8",$options['user'],$options['secret']);
					
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
	}
?>