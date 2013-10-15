<?PHP 
	/**
	 * The Output-Module
	 *
	 * @author Christian Blaschke
	 */

	namespace nrns\module;
	use nrns;
	
	class output extends nrns\module {
		
		public function __construct($injection, $app) {
			
			$injection->provideProviderClass("outputProvider", "nrns\\module\\output\\provider\\outputProvider");
			
			
		}
		
	}
?>