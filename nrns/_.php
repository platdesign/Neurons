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

	
	
	
	
	public function invoke($closure, $args=[]) {
		
		if(is_callable($closure)) {
			return call_user_func_array($closure, $args);
		} else if (is_object($closure)) {
			return call_user_func_array([$closure, '__invoke'], $args);
		}
		
		
	}
	

	public function properties($closure) {
		$ref = new ReflectionFunction($closure);
		return _::pluck($ref->getParameters(), 'name');
	}
	

	public function last($array) {
		return end($array);
	}


	public function slice($array, $a, $b) {
		return array_slice($array, $a, $b);
	}


	
}

?>