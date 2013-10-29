<?PHP

	namespace nrns;
	use nrns;

	class client {
	
		private $browser;
		public $lang;
		
		
		/**
		 * Loads browserinformation
		 *
		 * @author Christian Blaschke
		 */
		public function __construct() {
			//$this->browser = get_browser();
		}
	
	
	
		/**
		 * Returns the current IP-address of the client
		 *
		 * @return string IP
		 * @author Christian Blaschke
		 */
		public function getIp() {
			$ipaddress = '';
		
			$indices = ["HTTP_CLIENT_IP", "HTTP_X_FORWARDED_FOR", "HTTP_X_FORWARDED", "HTTP_FORWARDED_FOR", "HTTP_FORWARDED", "REMOTE_ADDR"];
		
			foreach($indices as $index) {
				if( isset( $_SERVER[$index] ) ) {
					return $_SERVER[$index];
				}
			}
		}
	
	
	
		/**
		 * Returns the OS of the client
		 *
		 * @return string OS
		 * @author Christian Blaschke
		 */
		public function getOs() {
			return $this->browser->platform;
		}


		
		/**
		 * Returns the browser-name of client
		 *
		 * @return string Browser
		 * @author Christian Blaschke
		 */
		public function getBrowser() {
			return $this->browser->comment;
		}



		/**
		 * Returns the language-short-string (e.g. "DE", "EN", etc.) of the client
		 *
		 * @return string Language
		 * @author Christian Blaschke
		 */
		public function getLanguage() {
			
			if( isset($_COOKIE['lang']) ) {
				
				return strtoupper($_COOKIE['lang']);
				
			} else if( isset($this->lang) ) {
				
				return strtoupper($this->lang);
				
			} else if( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
				
					// Parse the Accept-Language according to:
				    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
				    preg_match_all(
				       '/([a-z]{1,8})' . 		// M1 - First part of language e.g en
				       '(-[a-z]{1,8})*\s*' . 	// M2 -other parts of language e.g -us
				       '(;\s*q\s*=\s*((1(\.0{0,3}))|(0(\.[0-9]{0,3}))))?/i', // Optional quality factor M3 ;q=, M4 - Quality Factor
				       $_SERVER['HTTP_ACCEPT_LANGUAGE'],
				       $langParse);

				    $langs = $langParse[1]; // M1 - First part of language
				    $quals = $langParse[4]; // M4 - Quality Factor

				    $numLanguages = count($langs);
				    $langArr = array();

				    for ($num = 0; $num < $numLanguages; $num++)
				    {
				       $newLang = strtoupper($langs[$num]);
				       $newQual = isset($quals[$num]) ?
				          (empty($quals[$num]) ? 1.0 : floatval($quals[$num])) : 0.0;

				       // Choose whether to upgrade or set the quality factor for the
				       // primary language.
				       $langArr[$newLang] = (isset($langArr[$newLang])) ?
				          max($langArr[$newLang], $newQual) : $newQual;
				    }

				    // sort list based on value
				    // langArr will now be an array like: array('EN' => 1, 'ES' => 0.5)
				    arsort($langArr, SORT_NUMERIC);

				    // The languages the client accepts in order of preference.
				    $acceptedLanguages = array_keys($langArr);

				    return strtoupper($acceptedLanguages[0]);
				
			} else {
				return "EN";
			}
			
		}

		
		
	
		
	}

?>