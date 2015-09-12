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

	$talep_no = Util::cleanInput($_POST['talep_no']);
	$company_id = Util::cleanInput($_POST['company_id']);
	$offer_id = Util::cleanInput($_POST['offer_id']);
	
	$offerService = new OfferService();
	$result = $offerService->removeOffer($talep_no, $company_id, $offer_id);
	
	echo json_encode($result);
	
}

?>