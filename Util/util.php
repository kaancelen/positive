<?php

class Util{
	/**
	 * Function for stripping out malicious bits
	 * @param unknown $input
	 * @return mixed
	 */
	public static function cleanInput($input) {
		$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);
 
    	$output = preg_replace($search, '', $input);
    	return $output;
	}
	
	/**
	 * Sanitization function
	 * Uses the function above, as well as adds slashes as to not screw up database functions.
	 * @param unknown $input
	 * @return string
	 */
	public static function sanitize($input) {
	    if (is_array($input)) {
	        foreach($input as $var=>$val) {
	            $output[$var] = self::sanitize($val);
	        }
	    }
	    else {
	        if (get_magic_quotes_gpc()) {
	            $input = stripslashes($input);
	        }
	        $input  = self::cleanInput($input);
	        $output = mysql_real_escape_string($input);
	    }
	    return $output;
	}
	
	/**
	 * This function doesn't incorporate the 303 status code:
	 * @param unknown $url
	 * @param string $permanent
	 */
	public static function redirect($url, $permanent = false){
	    if (headers_sent() === false){
	    	header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
	    }
	    exit();
	}
	
	/**
	 * Read xml file and return content as nested array
	 * @param unknown $filepath
	 */
	public static function readXML($filepath){
		$xml = simplexml_load_file($filepath);
		return self::xml2array($xml);
	}
	
	/**
	 * convert xml object to array
	 * @param unknown $xmlObject
	 * @param unknown $out
	 * @return unknown
	 */
	private static function xml2array ( $xmlObject, $out = array () ){
	    foreach ( (array) $xmlObject as $index => $node ){
	    	$out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;
	    }
	
	    return $out;
	}
}

?>