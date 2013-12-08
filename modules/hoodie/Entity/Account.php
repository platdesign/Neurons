<?PHP

	namespace hoodie\Entity;

	class Account extends \mysql\Entity {

		protected $table = 'Account';
	
		public $id, $username, $secret;

		public static function createTable() {
		
			return \mysql::createTableQuery("CREATE TABLE IF NOT EXISTS `Account` (
	  			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  	  		`username` char(63) NOT NULL DEFAULT '',
	  		  	`secret` char(127) NOT NULL DEFAULT '',
	  		  	`createdAt` char(26) NOT NULL DEFAULT '',
	  		  	`updatedAt` char(26) NOT NULL DEFAULT '',
	  		  	PRIMARY KEY (`id`),
	  		  	UNIQUE KEY `username` (`username`)
				) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;")
			
				->on('fail', function(){
			
				});
		
		}

		public static function getById($id) {
			return \mysql::SingleSelectQuery('SELECT * FROM `Account` WHERE `id` = :id', ['id' => $id], get_called_class());
		}
	
		public static function getByUsername($username) {
			return \mysql::SingleSelectQuery('SELECT * FROM `Account` WHERE `username` = :username', ['username' => $username], get_called_class());
		}
	
	
		public function update() {
			$this->updatedAt = date('c');
			return \mysql::UpdateQuery('UPDATE `Account` SET `username` = :username, `secret` = :secret, `updatedAt` = :updatedAt WHERE `id` = :id;', [
				'username'	=>	$this->username,
				'secret'	=>	$this->secret,
				'id'		=>	$this->id,
				'updatedAt' =>	$this->updatedAt
			])
				->on("fail", function($error){
					throw mysql::EntityException('Account konnte nicht gespeichert werden.');
				});
		}
	
		public function create() {
		
			$this->createdAt = date('c');
			$this->updatedAt = $this->createdAt;
		
			return \mysql::InsertQuery('INSERT INTO `Account` (`username`, `secret`, `createdAt`, `updatedAt`) VALUES (:username, :secret, :createdAt, :updatedAt);', [
				'username'	=>	$this->username,
				'secret'	=>	$this->secret,
				'createdAt' =>	$this->createdAt,
				'updatedAt' =>	$this->updatedAt
			])
				->on("done", function($id){
					$this->id = $id;
				})
				->on("fail", function($error){
				
					$infoCode = $error->errorInfo[1];
				
					switch($infoCode) {
						case "1062":
							throw \mysql::EntityException('Der Username ('.$this->username.') ist bereits vergeben.');
						break;
					}
				
				});
				
		
		}
	

		public function setSecret($secret) {
			return password_hash($secret, PASSWORD_DEFAULT, ['cost'=>11]);
		}
	
	
	}

?>