<?PHP

	
	final class autoloader {
	
		static $dirs = array();
	
		static $spl_registered = false;
	
		public static function assign($dir) {
			// trailing slash
			if(substr($dir, -1) != "/") { $dir .= "/"; }
		
			static::$dirs[] = $dir;
		
			if( static::$spl_registered === false ) {
				spl_autoload_register( "autoloader::load" );
				static::$spl_registered = true;
			}
		}
	
		public static function load($class) {
			foreach(static::$dirs as $dir) {
				if( self::requireClass($dir,$class) ) {
					break;
				}
			}
		}
	
		private static function requireClass($dir,$searchFor) {
			//DIRECTORY_SEPARATOR
				
			if( !class_exists($searchFor) ) {
				$ns = $classname = "";
				$extension = '.php';
				
				
				//$searchFor = 'nrns\\provider\\provider';
				
				
				$searchFor = str_replace("\\", DIRECTORY_SEPARATOR, $searchFor);
				
				if($pos = strripos($searchFor, '/')) {
					
					$ns = substr($searchFor, 0, $pos+1);
					$classname = substr($searchFor, $pos+1);
				} else {
					$classname = $searchFor;
				}
				
				
				$lookat[] = $dir.$ns.$classname.$extension;
				$lookat[] = $dir.$ns.$classname.DIRECTORY_SEPARATOR.$classname.$extension;
				
				
				foreach($lookat as $file) {
					//echo $file."<br>";
					if( file_exists($file) ) {
						require_once($file);
						return true;
						break;
					}
				}
			}
		
		}
	
	}
	

	
?>