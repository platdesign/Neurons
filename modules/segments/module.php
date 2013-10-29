<?PHP 
	
	namespace segments;
	use nrns;
	
	$module = nrns::module('segments', ['router']);
	
	require 'segment.php';
	require 'segmentProvider.php';
	
	
	$module->provider("segmentProvider", __NAMESPACE__."\\segmentProvider");
	
?>