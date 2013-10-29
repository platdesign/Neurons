<?PHP

	namespace nrns;
	use nrns;
	
	require "fs/fsProvider.php";

	$module = nrns::module("fs", ['nrns']);

	$module->provider("fsProvider", "nrns\\fsProvider");
	
?>