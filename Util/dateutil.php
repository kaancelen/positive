<?php

class DateUtil{
	const FORMAT = "d/m/Y H:i:s";
	
	public static function format($date){
		$datetime = new DateTime($date);
		return $datetime->format(self::FORMAT);
	}
}

?>