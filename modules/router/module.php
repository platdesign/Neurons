<?PHP

	namespace router;
	use nrns;
	
	require "routeProvider.php";

	$module = nrns::module("router", ['nrns']);
	
	$module->provider("routeProvider", "router\\routeProvider");
	
	
	
	
	

?>