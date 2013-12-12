<?PHP 
	namespace nrns;


	class Dependencies {
	        public $sources = array();

	        public function __construct() {
                
	        }
        
	        /**
	         * Add source
	         * @param {String} name Name of file
	         * @param {Array} dependencies
	         */
	        public function add($name, $dependencies = array()) {
                
	                // If dependencies is a string, split into array
	                if(gettype($dependencies) == 'string') {
	                        $dependencies = preg_split('/,\s?/', $dependencies);
	                }
        
	                // Add 
	                $this -> sources[$name] = (object) array(
	                        'name' => $name, 
	                        'dependencies' => (array) $dependencies
	                );
	        }
        
	        /**
	         * Visit node, used in sorting
	         *
	         * This function checks all the dependencies of a node and calls 
	         * this function for each of them
	         */
	        private function visit($source, &$sources, &$sorted) {
                
	                // If source has not been visited
	                if (!$source -> visited) {
                
	                        // Set that source has been visited
	                        $source -> visited = true;
                        
	                        // Check each dependency
	                        foreach($source -> dependencies as $dependency) {
                        
	                                // Call this function for each source
	                                if(isset($sources[$dependency])) {
	                                        $this -> visit($sources[$dependency], $sources, $sorted);
	                                } else {
										throw new \Exception('The module "'.$source->name.'" depends on "'.$dependency.'" but its unavailable');
	                                }
                                
	                        }
                        
	                        // Add source to sorted array
	                        $sorted[] = $source;
	                }
	        }
        
	        /**
	         * Sort dependencies and return array with sorted sources
	         * 
	         * @returns {Array} Sorted sources as array
	         */
	        public function sort() {
	                $sources = $this -> sources;
	                $sorted = array();
                
	                // Reset visited
	                foreach($sources as $source) {
	                        $source -> visited = false;
	                }
                
	                // Loop through each source
	                foreach($sources as $source) {
	                        // Set visited to true
	                        $this -> visit($source, $sources, $sorted);
	                }
        
	                // Just return sources
	                return $sorted;
	        }
	}
	
	
?>