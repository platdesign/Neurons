<?PHP



nrns::module('nrns', [])
	
	->config(function(){
		
		require 'trait/methodcache.php';
		require 'service/request.php';
		require 'service/response.php';
		require 'service/scope.php';
		require 'service/client.php';
		require 'service/cookie.php';
		require 'service/session.php';
		require 'service/URI.php';

	})
	
	
	->service('request', 'nrns\\request')
	->service('response', 'nrns\\response')
	->service('rootScope', 'nrns\\scope')
	->service('client', 'nrns\\client')
	->service('cookie', 'nrns\\cookie')
	->service('session', 'nrns\\session')	
	->service('_URI', function(){
		return nrns\URI::createFromCurrent();
	})
		
		
		
		
	->service('pdo', function($nrns){
		
		$pdo = $nrns->newObject();
		
		$pdo->mysql = function($db, $user, $secret='', $host='localhost') {
			try {
				$pdo = new \PDO("mysql:host=$host;dbname=$db;charset=utf8",$user,$secret);
				$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			} catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
			
			return $pdo;
		};
		
		$pdo->sqlite = function($file) {
			$pdo = new \PDO("sqlite:".__SCRIPT__.DIRECTORY_SEPARATOR.$file);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			return $pdo;
		};
			
		return $pdo;
	
	})	
		
	;


?>