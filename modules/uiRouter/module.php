<?PHP 
	
	namespace uiRouter;
	use nrns;
	
	$module = nrns::module("uiRouter", ["nrns"]);
	
	require "segment.php";
	require "uiRouteProvider.php";
	
	$module->provider("uiRouteProvider", __NAMESPACE__."\\uiRouteProvider");
	
?>