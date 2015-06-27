<?php
include_once (__DIR__.'/../Util/init.php');

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
	
	$card_id = Util::cleanInput($_POST['card_id']);
	$card_name = Util::cleanInput($_POST['card_name']);
	$card_no = Util::cleanInput($_POST['card_no']);
	$expire_date = Util::cleanInput($_POST['expire_date']);
	$cvc = Util::cleanInput($_POST['cvc']);
	
	$agentService = new AgentService();
	$response = $agentService->updateCardInfo($card_id, $card_name, $card_no, $expire_date, $cvc);
	if($response){
		$logger->write(ALogger::DEBUG, __FILE__, "Credit card [".$card_id."] updated by [".$user[User::NAME]."]");
	}
	
	echo json_encode($response);
}
?>