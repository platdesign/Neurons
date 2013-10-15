<?PHP 
	

	namespace nrns\module;
	use nrns;
	
	class uiRouter extends nrns\module {
		
		public function __construct($injection, $app) {
			
			$app->modules(["router"]);
			$injection->provideProviderClass("uiRouteProvider", "nrns\\module\\uiRouter\\uiRouteProvider");
		
			//echo "<pre>"; print_r($injection->getActiveProviders());
			
		}
		
	}
?>