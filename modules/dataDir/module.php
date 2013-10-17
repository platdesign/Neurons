<?PHP

	namespace dataDir;
	use nrns;
	
	require "dataDirectory.php";
	require "item.php";
	require "group.php";
	require "itemInfo.php";
	
	require "provider.php";
	

	$module = nrns::module("dataDir", ["nrns"]);

	$module->provider("dataDirProvider", "dataDir\\dataDirProvider");
?>