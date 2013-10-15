<?PHP 
	/**
	 * The Request-Module includes following providers
	 * - requestProvider
	 * - clientProvider
	 *
	 * @author Christian Blaschke
	 */

	namespace nrns\module;
	use nrns;
	
	class request extends nrns\module {
		
		public function __construct($injection, $app) {
			
			$injection->provideClassInstance("requestProvider", "nrns\\module\\request\\components\\request");
			$injection->provideClassInstance("clientProvider", "nrns\\module\\request\\components\\client");
			
			
			
			
			
		}
		
	}
?>