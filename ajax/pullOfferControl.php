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
			$logger->write(ALogger::INFO, __FILE__, "Request not from branch");
			echo json_encode(false);
			return;
		}
	}
	
	$searchService = new SearchService();
	$response = $searchService->checkNewPolicyRequest(Util::cleanInput($_POST['request_id']));
	
	echo json_encode($response);
}
?>