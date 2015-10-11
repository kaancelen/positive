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
	$request_number = Util::cleanInput($_POST['request_number']);
	$cookieString = Util::cleanInput($_POST['cookie_companies']);
	
	$cookieString = str_replace(array("[", "]", "\""), "", $cookieString);
	$cookieCompanies = explode(",", $cookieString);
	
	$offerService = new OfferService();
	$result = $offerService->getAllRequests(null, $cookieCompanies, $request_number);
	for ($i = 0; $i < count($result); $i++){
		$result[$i]['OFFER_RATIO'] = $offerService->getGivenOfferRatio($result[$i]['ID']);
		$result[$i]['CREATION_DATE'] = DateUtil::format($result[$i]['CREATION_DATE']);
	}
	
	echo json_encode($result);
}

?>