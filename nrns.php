<?PHP


	define("nrns", true);
	define("version", "0.0.2");
	define("author", "Christian Blaschke");
	define("contact", "mail@platdesign.de");

	
	error_reporting(-1);
	set_error_handler(function($level, $message, $file, $line, $context){
		
		$levels =[
			2047 	=> 'E_ALL',
			1024 	=> 'E_USER_NOTICE',
			512 	=> 'E_USER_WARNING',
			256 	=> 'E_USER_ERROR',
			128 	=> 'E_COMPILE_WARNING',
			64 		=> 'E_COMPILE_ERROR',
			32 		=> 'E_CORE_WARNING',
			16 		=> 'E_CORE_ERROR',
			8 		=> 'E_NOTICE',
			4 		=> 'E_PARSE',
			2		=> 'E_WARNING',
			1 		=> 'E_ERROR'
		];
		
		$error = (object) [
			"level"		=> 	$level,
			"type"		=>	$levels[$level],
			"message"	=>	$message,
			"file"		=>	$file,
			"line"		=>	$line,
			"context"	=>	$context
		];
		
		
		nrns::logError($error);
		
	});




	


	final class nrns {
		
		
		private static $injectionProvider, $inited, $errorlog=[];
		public static $rootpath, $nrnspath, $devMode=FALSE, $bootTS, $endTS;
		
		public static function init() {
			self::$bootTS = self::$endTS = microtime(1);
			
			self::$rootpath = dirname($_SERVER['SCRIPT_FILENAME']);;
			self::$nrnspath = dirname(__FILE__);
			
			self::loadCore();
			self::initInjectionProvider();
			
			register_shutdown_function("nrns::run");
			self::$inited = true;
		}
		
		public static function devMode() {
			self::$devMode = TRUE;
		}
		
		private static function loadCore() {
			
			require self::$nrnspath . "/core/autoloader.php";
			require self::$nrnspath . "/core/functions.php";
			
			
			autoloader::assign(self::$nrnspath."/core/");
			autoloader::assign(self::$nrnspath."/extensions/");
			autoloader::assign(self::$nrnspath."/plugins/");

		}
		
		public static function injectionProvider() {
			return self::$injectionProvider;
		}

		private static function initInjectionProvider() {
			self::$injectionProvider = new nrns\provider\injectionProvider();
		}
		
		
		public static function logError($errorObject) {
			$errorObject->timestamp = microtime(1);
			self::$errorlog[] = $errorObject;
		}
		
		public static function errorlog(){
			return self::$errorlog;
		}
		
		
		public static function invoke($input, $scope=null) {
			return self::injectionProvider()->invoke($input, $scope);
		}
		
		public static function provider($name) {
			return self::injectionProvider()->getProvider($name);
		}
		public static function service($name) {
			return self::injectionProvider()->getProvider($name)->getService();
		}
		
		
		
		
		
		public static function module($name, $modules=null) {
			if( !self::$inited ) { self::init(); }
			
			if(is_array($modules)) {
				
				return nrns::invoke(function($moduleProvider)use($name, $modules){
					return $moduleProvider->createModule($name, $modules);
				});

			} else {
				
				return nrns::invoke(function($module)use($name){
					return $module($name);
				});
				
			}
			
		}
		
		public static function loadModules($modules=[]) {
			
			return nrns::invoke(function($moduleProvider)use($modules){
			
				$moduleProvider->loadModules($modules);
			
			});
			
		}
		
		
		public static function run() {
			try {
				self::provider("nrns")->run();
				
			}catch(Exception $e){
				clog("NRNS ERROR: ".$e->getMessage());
			}
			
		}
		
		public static function getExecuteTime() {
			
			return round(self::$endTS - self::$bootTS, 5);
		}
		
		public static function clogExecutionDuration() {
			register_shutdown_function(function(){
				nrns::$endTS = microtime(1);
				clog(["Execution Duration"=>nrns::getExecuteTime()]);
			});
		}
		
		public static function requireLib($name) {
			$filename = self::$nrnspath."/libs/".$name.".php";
			
			if( file_exists($filename) ) {
				require_once $filename;
			}
		}
		
		
		
		
		
		
		
		
		
		
		
		
	
		public static function autoload($path) {
			autoloader::assign(self::$rootpath . "/" . $path);
		}
	
		
	
		public static function loadTemplateContent($filename, $globals=[]) {
			if( file_exists($filename) ) {
				extract($globals);
				unset($globals);
				ob_start();
					require $filename;
				$content = ob_get_contents();
				ob_end_clean();
				return $content;
			}
		}
	
		public static function displaySysError($error) {
			if(self::$devMode) {
				echo '<div style="border:1px solid red; margin: 20px; padding: 20px;border-radius: 3px; line-height: 1.4em; font-family: courier; font-size: .9em;"><b>NRNS-Error!</b><hr>'.$error.'</div>';
				die();
			}
			
		}
	
		
		
	
		
		
		/* Helper */
		public static function extendObj($obj1, $obj2) {
		
			foreach($obj2 as $key => $val) {
				if( is_object($val) ) {
					if( isset($obj1->{$key}) ) {
						self::extendObj($obj1->{$key}, $val);
					} else {
						$obj1->{$key} = $val;
					}
				} else {
					$obj1->{$key} = $val;
				}
			}
		
			return clone $obj1;
		
		}
	
	
		/**
		 * Returns a JSClosure-Instance
		 *
		 * @param string $closure 
		 * @param string $scope 
		 * @return JSClosure
		 * @author Christian Blaschke
		 */
		public static function closure($closure, $scope=null) {
			return new nrns\JSClosure($closure, $scope);
		}
		
		
		
		
		
		
		
		
	}


?>