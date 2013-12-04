<?PHP

	namespace express;
	use nrns;
	
	
	$module = nrns::module("express", ['fs', 'router']);
	
	
	require "expressProvider.php";
	require "resource.php";
	
	$module->provider("expressProvider", "express\\expressProvider");
	
?>