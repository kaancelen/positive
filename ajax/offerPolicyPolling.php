<?php
include_once (__DIR__.'/../Util/init.php');
include_once (__DIR__.'/../service/SearchService.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}else{
		$user = Session::get(Session::USER);
		if($user[User::ROLE] != User::PERSONEL){
			$logger->write(ALogger::INFO, __FILE__, "Request not from personel");
			echo json_encode(false);
			return;
		}
	}
	
	$companies = array();
	$post_companies = Util::cleanInput($_POST['companies']);
	if(!is_null($post_companies)){
		$companies = explode(",", $post_companies);
	}
	$last_enter_offer_req = urldecode(Util::cleanInput($_POST['last_enter_offer_req']));
	$last_enter_policy_req = urldecode(Util::cleanInput($_POST['last_enter_policy_req']));

	$searchService = new SearchService();
	$response = $searchService->checkNewOfferPolicy($companies, $last_enter_offer_req, $last_enter_policy_req);
	if($response[0] > 0){
		Cookie::put(Cookie::LE_OFFER_FLAG, "on", Cookie::REMEMBER_EXPIRE);
	}
	if($response[1] > 0){
		Cookie::put(Cookie::LE_POLICY_FLAG, "on", Cookie::REMEMBER_EXPIRE);
	}
	$response[2] = $searchService->checkNewCancelRequest($last_enter_policy_req);
	if($response[2] > 0){
		Cookie::put(Cookie::LE_POLICY_FLAG, "on", Cookie::REMEMBER_EXPIRE);
	}
	
	echo json_encode($response);
}
?>