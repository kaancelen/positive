<?php
class Session{
	
	const USER = "USER";
	const FLASH = "FLASH";
	
	#check if name exist in session
	public static function exists($name){
		return (isset($_SESSION[$name])) ? true : false;
	}
	#set given value to session attribute
	public static function put($name, $value){
		return $_SESSION[$name] = $value;
	}
	#get session's name value
	public static function get($name){
		return $_SESSION[$name];
	}
	#delete given name from session
	public static function delete($name){
		if(self::exists($name)){
			unset($_SESSION[$name]);
		}
	}
	#it contains flash messages
	public static function flash($name, $string = ''){
		if(self::exists($name)){
			$session = self::get($name);
			self::delete($name);
			return $session;
		}else{
			self::put($name, $string);
		}
	}
}
?>