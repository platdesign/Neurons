<?PHP



nrns::module('nrns', [])
	
	->config(function(){
		
		require 'trait/methodcache.php';
		require 'service/request.php';
		require 'service/response.php';
		require 'service/scope.php';
		require 'service/client.php';
		require 'service/cookie.php';

	})
	
	->service('nrns.version', function(){
		return NRNS_VERSION;
	})
	->service('request', 'nrns\\request')
	->service('response', 'nrns\\response')
	->service('rootScope', 'nrns\\scope')
	->service('client', 'nrns\\client')
	->service('cookie', 'nrns\\cookie')
	;


?>