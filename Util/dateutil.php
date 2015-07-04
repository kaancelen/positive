<?php

class DateUtil{
	const FORMAT = "d/m/Y H:i:s";
	const HOUR_FORMAT = "H:i";
	
	public static function format($date){
		$datetime = new DateTime($date);
		return $datetime->format(self::FORMAT);
	}
	
	public static function hour_format($date){
		$datetime = new DateTime($date);
		return $datetime->format(self::HOUR_FORMAT);
	}
}

?>