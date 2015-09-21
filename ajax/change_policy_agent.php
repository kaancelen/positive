<?php
include_once (__DIR__.'/../Util/init.php');

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
	
	$request_id = Util::cleanInput($_POST['request_id']);
	$offer_id = Util::cleanInput($_POST['offer_id']);
	$new_user_id = Util::cleanInput($_POST['new_user_id']);
	
	$agentService = new AgentService();
	$result = $agentService->changePolicyAgent($request_id, $offer_id, $new_user_id);
	
	echo json_encode($result);
}

?>