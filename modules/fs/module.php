<?PHP

	namespace nrns;
	use nrns;
	
	require "fs/fsProvider.php";

	$module = nrns::module("fs", []);
	
	$module->config(function($nrns, $fsProvider){
		$fsProvider->setRootPath($nrns->getRootpath());
	});
	
	$module->provider("fsProvider", "nrns\\fsProvider");
	
	
	

?>