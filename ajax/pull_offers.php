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
	
	$request_id = Util::cleanInput($_POST['request_id']);
	$offerService = new OfferService();
	$offers = $offerService->getOffers($request_id);
	
	echo json_encode($offers);
}
?>