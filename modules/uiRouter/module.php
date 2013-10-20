<?PHP 
	
	namespace uiRouter;
	use nrns;
	
	$module = nrns::module("uiRouter", ["nrns", "router"]);
	
	require "segment.php";
	require "uiRouteProvider.php";
	
	$module->provider("uiRouteProvider", __NAMESPACE__."\\uiRouteProvider");
	
?>