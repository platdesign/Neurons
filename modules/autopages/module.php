<?PHP 
	

	namespace autopages;
	use nrns;
	
	require "autopagesProvider.php";
	
	$module = nrns::module("autopages", ["fs", "uiRouter"]);
	
	$module->provider("autopagesProvider", "nrns\\module\\autopages\\autopagesProvider");
?>