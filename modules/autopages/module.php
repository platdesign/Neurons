<?PHP 
	

	namespace autopages;
	use nrns;
	
	require "autopagesProvider.php";
	
	$module = nrns::module("autopages", ["fs", "segments"]);
	
	$module->provider("autopagesProvider", "nrns\\module\\autopages\\autopagesProvider");
?>