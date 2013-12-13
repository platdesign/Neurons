<?PHP

define('nrns', true);
define('NRNS_VERSION', '0.1.0');

define('__SCRIPT__', dirname($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$_SERVER['SCRIPT_NAME']));



require 'nrns/_.php';
require 'nrns/autoloader.php';

nrns::init();

require 'nrns/module/nrns.php';












final class nrns {
	public static $injection;
	private static $autoloader;
	
	public static function init() {
		self::$autoloader = new nrns\autoloader();
		
		nrns::autoloader()->watch();
		nrns::autoloader()->watch('vendor/underscore');
		
		$ip = nrns::$injection = new nrns\provider\injectionProvider();
		
		$ip->provide('moduleProvider', 'nrns\provider\moduleProvider');
		$ip->provide('nrnsProvider', 'nrns\provider\nrnsProvider');
		
		
		register_shutdown_function('nrns::run');
	}



	
	public static function module($name, $deps=null) {

		if( is_array($deps) ) {
			return nrns::$injection->provider('moduleProvider')->create($name, $deps);
		} else {
			return nrns::$injection->provider('moduleProvider')->get($name);
		}
	}

	
	public static function autoloader() {
		return self::$autoloader;
	}



	
	public static function run() {
		try {
			nrns::$injection->provider('moduleProvider')->wakeUpModules();
			nrns::$injection->provider('nrnsProvider')->trigger('runApp');
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	

	
	
	
	
	
	
	
	public static function Exception($message) {
		return new NRNSException($message);
	}
	
	
	
}

class NRNSException extends Exception {}

?>