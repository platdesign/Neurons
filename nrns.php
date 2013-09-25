<?PHP
define("nrns", true);


	final class nrns {
		
		public static function application(){
			$backtrace = debug_backtrace();

			$rootpath = dirname($backtrace[0]['file']);
			$nrnspath = dirname(__FILE__);
			
			require $nrnspath . "/core/autoloader.php";
			require $nrnspath . "/core/functions.php";
			
			self::loadCore($nrnspath);
			
			$app = new nrns\app($nrnspath, $rootpath);
			
			return $app;
		}
	
		private static function loadCore($corepath) {
			autoloader::assign($corepath."/core/");
			autoloader::assign($corepath."/extensions/");
			autoloader::assign($corepath."/plugins/");

		}
	
		public static function autoload($path) {
			autoloader::assign(self::$rootpath . "/" . $path);
		}
	
		public static function closure($closure, $scope=null) {
			return new nrns\JSClosure($closure, $scope);
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
			echo '<div style="border:1px solid red; margin: 20px; padding: 20px;border-radius: 3px; line-height: 1.4em; font-family: courier; font-size: .9em;"><b>NRNS-Error!</b><hr>'.$error.'</div>';
			die();
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
	
	
		
		
		
		
	}


?>