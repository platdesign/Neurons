<?PHP

	namespace nrns;
	use nrns;
	
	require "routeProvider.php";

	$module = nrns::module("router", []);
	
	$module->provider("routeProvider", "nrns\\routeProvider");
	
	
	
	
	

?>