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
		if($user[User::ROLE] != User::BRANCH){
			$logger->write(ALogger::INFO, __FILE__, "Request not from branch");
			echo json_encode(false);
			return;
		}
	}
	
	$last_enter_policy_page = urldecode(Util::cleanInput($_POST['last_enter_policy_page']));
	$last_enter_offer_resp = urldecode(Util::cleanInput($_POST['last_enter_offer_resp']));
	$last_enter_policy_req_page = urldecode(Util::cleanInput($_POST['last_enter_policy_req_page']));

	$searchService = new SearchService();
	$response = $searchService->checkNewPolicy($user[User::ID], $last_enter_policy_page, $last_enter_offer_resp);
	if($response[0] > 0){
		Cookie::put(Cookie::LE_OFFER_RESP_FLAG, "on", Cookie::REMEMBER_EXPIRE);
	}
	if($response[1] > 0){
		Cookie::put(Cookie::LE_POLICY_PAGE_FLAG, "on", Cookie::REMEMBER_EXPIRE);
	}
	$response[2] = $searchService->checkNewCancelResponse($user[User::ID], $last_enter_policy_req_page);
	if($response[2] > 0){
		Cookie::put(Cookie::LE_POLICY_REQ_PAGE_FLAG, "on", Cookie::REMEMBER_EXPIRE);
	}
	
	echo json_encode($response);
}
?>