<?PHP

	namespace hoodie;
	use nrns;
	
	

	$module = nrns::module('hoodie', ['express', 'coreData']);

	$module->config(function($express){
		
		
		$express->addExceptionHandler("hoodie\\hoodieAccountException", function($e){
			$this->error(401, $e->getMessage());
		});
		
		
		$express->get("/account", function($hoodie) {
			
			$hoodie->account->isOnline();
			
			return $hoodie->account;
		});
		
		$express->post("/account/signup", function($hoodie, $request) {
			$body = json_decode($request->getBody());
			
			$hoodie->account->signup($body->username, $body->secret);
			
			return $hoodie->account;
		});
		
		$express->post("/account/signin", function($hoodie) {
			$body = json_decode($request->getBody());
			
			$hoodie->account->signin($body->username, $body->secret);
			
			return $hoodie->account;
		});
		
		
		$express->post("/account/signout", function($hoodie) {
			
			$hoodie->account->signout();
			
			return $hoodie->account;
		});
		
		$express->post("/account/destroy", function($hoodie) {
			
			$hoodie->account->destroy();
			$hoodie->account->signout();
			
			return $hoodie->account;
		});
		
	});
	
	
	
	
	
	
	
	$module->provider("hoodieProvider", "hoodie\\hoodieProvider");
	
	class hoodieProvider extends nrns\provider\provider {
		
		public function __construct($injection) {
			
			$this->account = $injection->invoke("hoodie\\hoodieAccount");
			$this->account->__setHoodie($this);
			
		}
		
		public function setDB($db) {
			$this->db = $db;
		}
		
	} 
	
	
	class hoodieAccountException extends \Exception {
		
	}
	
	class hoodieAccount {
		public $id, $username;
		private $hoodie;
		
		
		public function __construct() {

		}
		
		public function __setHoodie($hoodie) { $this->hoodie = $hoodie; }
		
		
		public function isOnline() {
			if(1!==1) {
				// is Online
			} else {
				// is Offline
				throw new hoodieAccountException("User ist not online");
				
			}
		}
		
		public function singIn($username, $secret) {
			
		}
		
		public function signOut() {
			
		}
		
		public function signUp($username, $secret) {
			
		}
		
		public function destroy() {
			
		}
		
		
	}
?>