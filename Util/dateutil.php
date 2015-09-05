<?php

class DateUtil{
	const FORMAT = "d/m/Y H:i:s";
	const HOUR_FORMAT = "H:i";
	const DB_DATE_FORMAT = "Y-m-d";
	const DB_DATE_FORMAT_TIME = "Y-m-d H:i:s";
	
	const OFFER_REQUEST_TIMEOUT_MILLIS = 86400;//24 hour
	const POLICY_REQUEST_TIMEOUT_MILLIS = 604800;//7 day
	const POLICY_TIMEOUT_MILLIS = 2592000;//30 day
	
	public static function format($date){
		if(is_null($date)){
			return null;
		}
		$datetime = new DateTime($date);
		return $datetime->format(self::FORMAT);
	}
	
	public static function hour_format($date){
		$datetime = new DateTime($date);
		return $datetime->format(self::HOUR_FORMAT);
	}
	
	public static function after($start, $end, $difference){
		return (($end - $start) > $difference ? true : false);
	}
}

?>