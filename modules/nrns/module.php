<?PHP

	namespace nrns;
	use nrns;
	
	require "provider/client.php";
	require "provider/request.php";
	require "provider/errorProvider.php";
	require "provider/responseProvider.php";

	require "provider/cookie.php";
	

	$module = nrns::module("nrns", []);
	
	$module->provider("errorProvider", "nrns\\errorProvider");
	
	$module->service("request", "nrns\\request");
	$module->service("client", "nrns\\client");

	$module->provider("responseProvider", "nrns\\responseProvider");
	
	
	$module->service("cookie", function(){
		return new cookie();
	});
	

?>