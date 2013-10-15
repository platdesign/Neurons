<?PHP 
	

	namespace nrns\module;
	use nrns;
	
	class router extends nrns\module {
		
		public function __construct($injection, $app) {
			
			$app->modules(["request", "output"]);
			$injection->provideProviderClass("routeProvider", "nrns\\module\\router\\provider\\routeProvider");
		
			//echo "<pre>"; print_r($injection->getActiveProviders());
			
		}
		
	}
?>