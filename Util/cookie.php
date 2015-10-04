<?php
#Cookie operations
class Cookie{
	
	const COMPANIES = "companies";

	const LAST_ENTER_OFFER_REQ = "last_enter_offer_req";
	const LE_OFFER_FLAG = "le_offer_flag";

	const LAST_ENTER_POLICY_REQ = "last_enter_policy_req";
	const LE_POLICY_FLAG = "le_policy_flag";

	const LAST_ENTER_POLICY_PAGE = "last_enter_policy_page";
	const LE_POLICY_PAGE_FLAG = "le_policy_page_flag";

	const LAST_ENTER_OFFER_RESP = "last_enter_offer_resp";
	const LE_OFFER_RESP_FLAG = "le_offer_resp_flag";
	
	const LAST_ENTER_POLICY_REQ_PAGE = "last_enter_policy_req_page";
	const LE_POLICY_REQ_PAGE_FLAG = "le_policy_req_page_flag";
	
	const LAST_ENTER_POLICY_CANCEL = "last_enter_policy_cancel";
	const LE_POLICY_CANCEL_FLAG = "le_policy_cancel_flag";
	
	const HASH = "HASH";
	const REMEMBER_EXPIRE = 604800;
	
	#if given named cookie exists
	public static function exists($name){
		return (isset($_COOKIE[$name])) ? true : false;
	}
	#get given named cookie
	public static function get($name){
		return $_COOKIE[$name];
	}
	#add cookie
	public static function put($name, $value, $expiry){
		if(setcookie($name, $value, time() + $expiry, '/')){
			return true;
		}
		return false;
	}
	#delete cookie
	public static function delete($name){
		self::put($name, '', time() - 1);
	}
}
?>