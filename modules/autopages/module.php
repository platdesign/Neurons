<?PHP 
	

	namespace autopages;
	use nrns;
	
	require "autopagesProvider.php";
	
	$module = nrns::module("autopages", ["uiRouter"]);
	
	$module->provider("autopagesProvider", "nrns\\module\\autopages\\autopagesProvider");
?>