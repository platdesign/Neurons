<?PHP

	namespace nrns;
	use nrns;
	
	require "client.php";
	require "request.php";
	require "provider/errorProvider/errorProvider.php";
	
	require "provider/outputProvider/outputProvider.php";

	$module = nrns::module("nrns", []);
	
	$module->provider("errorProvider", "nrns\\errorProvider");
	
	$module->service("request", "nrns\\request");
	$module->service("client", "nrns\\client");

	$module->provider("outputProvider", "nrns\\outputProvider");
	
	
	
	

?>