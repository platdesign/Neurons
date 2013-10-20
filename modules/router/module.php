<?PHP

	namespace nrns;
	use nrns;
	
	require "routeProvider.php";

	$module = nrns::module("router", ['nrns']);
	
	$module->provider("routeProvider", "nrns\\routeProvider");
	
	
	
	
	

?>