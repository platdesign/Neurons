<?PHP 
	

	namespace nrns\module;
	use nrns;
	
	class autopages extends nrns\module {
		
		public function __construct($injection, $app) {
			
			$app->modules(["uiRouter"]);
			$injection->provideProviderClass("autopagesProvider", "nrns\\module\\autopages\\autopagesProvider");
		
			//echo "<pre>"; print_r($injection->getActiveProviders());
			
		}
		
	}
?>