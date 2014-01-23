<?PHP

/**
 * UNDERSCORE
 *
 * @package default
 * @author Christian Blaschke
 */
class _ {
	
	public static function getProperty($objOrArray, $propertyName) {
		if( is_array($objOrArray) ) {
			if( isset($objOrArray[$propertyName]) ) {
				return $objOrArray[$propertyName];
			}
		} elseif( is_object($objOrArray) ) {
			if( isset($objOrArray->{$propertyName}) ) {
				return $objOrArray->{$propertyName};
			}
		}
	}
	
	
	
	/** pluck
	 * JS-Doc from underscore.org
	 *
	 * var stooges = [{name: 'moe', age: 40}, {name: 'larry', age: 50}, {name: 'curly', age: 60}];
	 * _.pluck(stooges, 'name');
	 * => ["moe", "larry", "curly"]
	 *
	 * @param string $list 
	 * @param string $propertyName 
	 * @return void
	 * @author Christian Blaschke
	 */
	public static function pluck($list, $propertyName) {
		$result = [];
		foreach($list as $item) {
			$result[] = self::getProperty($item, $propertyName);
		}
		return $result;
	}

	
	
	
	
	public static function invoke($closure, $args=[]) {
		
		if(is_callable($closure)) {
			return call_user_func_array($closure, $args);
		} else if (is_object($closure)) {
			return call_user_func_array([$closure, '__invoke'], $args);
		}
		
		
	}
	

	public static function properties($closure) {
		if( is_string($closure) && strpos($closure, '::') != 0 ) {
			$ref = new ReflectionMethod($closure);
		} else {
			$ref = new ReflectionFunction($closure);
		}
		
		return _::pluck($ref->getParameters(), 'name');
	}
	

	public static function last($array) {
		return end($array);
	}


	public static function slice($array, $a, $b) {
		return array_slice($array, $a, $b);
	}

	public static function parseDotString($dotstring, $scope) {
		
		$parts = explode('.', $dotstring);
		if(count($parts)>1) {
			
			$active = $scope;
			if( is_array($active) ) {
				foreach($parts as $part) {
				
					if( isset($active[$part]) ) {
						$active = $active[$part];
					} else {
						return false;
					}
				
				}
			}
		
			if( is_object($active) ) {
				foreach($parts as $part) {
				
					if( isset($active->{$part}) ) {
						$active = $active->{$part};
					} else {
						return false;
					}
				
				}
			
			}
			return $active;
		} else {
			if( $active = self::getProperty($scope, $dotstring) ) {
				return $active;
			}
		}
		
	}
	
}

?>