<?PHP 
	
	namespace nrns;
	use nrns;
	
	

	class errorProvider extends nrns\provider\provider {
		
		use events;
		
		public function __construct($nrns) {
			$this->nrns = $nrns;
			
			$this->nrns->on("shutdown", function(){
				$this->trigger("log");
			});
			
			if( $this->nrns->devMode() ) {
				$this->logInConsole();
			}
		}
		
		public function getErrors() {
			return nrns::errorlog();
		}
		
		public function logInConsole() {
			
			$this->on("log", function(){
				$errors = $this->getErrors();
				if($errors) {
					foreach($errors as $error) {
						clog($error);
					}
					
				}
				
			});
			
		}
	}
	
	

?>