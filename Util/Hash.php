<?php
class Hash{
	#add given string and salt then hash it use sha256 algorithm
	public static function make($string, $salt = ''){
		return hash('sha256', $string . $salt);
	}
	#return random string
	public static function salt($length){
		return mcrypt_create_iv($length);
	}
	#return random and uniq hashed string
	public static function unique(){
		return self::make(uniqid());
	}
}
?>