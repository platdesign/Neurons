<?PHP

define('nrns', true);
define('version', '0.1.0');

define('__SCRIPT__', dirname($_SERVER['SCRIPT_FILENAME']));


error_reporting(-1);
set_error_handler('nrns::errorHandler');
register_shutdown_function('nrns::run');



require 'nrns/_.php';
require 'nrns/autoloader.php';

nrns::init();

require 'nrns/module/nrns.php';





final class nrns {
	public static $injection;
	private static $autoloader;
	private static $error;
	
	
	public static function init() {
		self::$autoloader = new nrns\autoloader();
		
		nrns::autoloader()->watch();
		nrns::autoloader()->watch('vendor/underscore');
		
		$ip = nrns::$injection = new nrns\provider\injectionProvider();
		
		$ip->provide('moduleProvider', 'nrns\provider\moduleProvider');
		$ip->provide('nrnsProvider', 'nrns\provider\nrnsProvider');
		
		
		
	}


	public static function errorHandler() {
		self::$error = func_get_args();
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


	public static function devMode() {
		nrns::$injection->provider('nrnsProvider')->devMode = true;
	}
	
	public static function run() {
		
		try {
			$nrns = nrns::$injection->provider('nrnsProvider');
			
			if( self::$error ) {
				$e = self::$error;
				throw nrns::Exception('<b>' . $e[1]. '</b> in '. $e[2] . ' on line ' . $e[3], $e[0]);
			}
			
			nrns::$injection->provider('moduleProvider')->wakeUpModules();
			$nrns->trigger('runApp');
		} catch(Exception $e) {
			if($nrns->devMode) {
				echo $e->getMessage();
			} else {
				echo 'An error has occurred. Please contact the admin.';
			}
			
		}
	}
	

	
	public static function Exception($message) {
		return new NRNSException($message);
	}
	
	
	
}

class NRNSException extends Exception {}

?>