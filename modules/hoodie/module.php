<?PHP

	namespace hoodie;
	use nrns;
	@session_start();
	$module = nrns::module('hoodie', ['express', 'mysql']);

	require "Entity/Account.php";
	

	class Account {
		private $hoodie;
		public $id, $username;
		
		public function __construct($hoodie) {
			$this->hoodie = $hoodie;
		}
		
		public function isOnline() {
			if( isset($_SESSION['_Account']) ) {
				
				$account = Entity\Account::getById($_SESSION['_Account'])->exec($this->hoodie->pdo);
				
				if($account->id) {
					$this->id 		= $account->id;
					$this->username = $account->username;
					return true;
				}
				
			} else {
				return false;
			}
		}
		
		public function signUp($username, $secret) {
			$account = new Entity\Account([
				'username' 	=> 	$username,
				'secret'	=>	$secret
			]);
				
			$account->save()->exec($this->hoodie->pdo);
			
			$this->id 		= $account->id;
			$this->username = $account->username;
			
			$this->signIn($username, $secret);
		}
		
		public function signIn($username, $secret) {
			$account = Entity\Account::getByUsername($username)->exec($this->hoodie->pdo);
			
			if(isset($account->id) AND password_verify($secret, $account->secret)) {
				$this->id 		= $account->id;
				$this->username = $account->username;
				
				$_SESSION['_Account'] = $account->id;
			}
			
		}
		
		public function signout() {
			unset($_SESSION['_Account']);
		}
		
	}
	
	class hoodie {
		public $account;
		
		public function __construct() {
			$this->account = new Account($this);
		}
		
		public function pdo($pdo=null) {
			if(isset($pdo)) {
				$this->pdo = $pdo;
			} else {
				return $this->pdo;
			}
		}
	}
	
	$module->service("hoodie", function(){
		return new hoodie();
	});
?>