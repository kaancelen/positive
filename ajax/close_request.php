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
	
	$type = Util::cleanInput($_POST['type']);
	$request_id = Util::cleanInput($_POST['request_id']);
	
	$offerService = new OfferService();
	$result = $offerService->closeRequest($request_id, $type);
	
	echo json_encode($result);
}

?>