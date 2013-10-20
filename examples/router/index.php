<?PHP

	require "../../nrns.php";

	nrns::devMode();
	$app = nrns::module('app', ['router']);
	
	
	
	$app->config(function($routeProvider){
	
		$routeProvider
			->when("/", function(){
				echo 'Hello World';
			})
				
			->when("/:name", function($route){
				echo 'Hello '.$route->params->name;
			})
				
			->otherwise(["redirect"=>"/"]);
	
	});
	
?>