<?PHP



nrns::module('nrns', [])
	
	->config(function(){
		
		require 'trait/methodcache.php';
		require 'service/request.php';
		require 'service/response.php';
		require 'service/scope.php';

	})
	
	->service('nrns.version', function(){
		return NRNS_VERSION;
	})
	->service('request', 'nrns\\request')
	//->provider('responseProvider', 'nrns\\responseProvider');
	->service('response', 'nrns\\response')
	->service('rootScope', 'nrns\\scope')
	;


?>