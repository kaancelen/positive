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

	$user_id = Util::cleanInput($_POST['user_id']);
	$talep_no = Util::cleanInput($_POST['talep_no']);
	$company_id = Util::cleanInput($_POST['company_id']);
	$prim = Util::cleanInput($_POST['prim']);
	$komisyon = Util::cleanInput($_POST['komisyon']);
	$prod_komisyon = Util::cleanInput($_POST['prod_komisyon']);
	
	$prim = str_replace(".", "", $prim);
	$prim = str_replace(",", ".", $prim);
	$prim = floatval($prim);
	
	$komisyon = str_replace(".", "", $komisyon);
	$komisyon = str_replace(",", ".", $komisyon);
	$komisyon = floatval($komisyon);
	
	$offerService = new OfferService();
	$result = $offerService->addOffer($user_id, $talep_no, $company_id, $prim, $komisyon, $prod_komisyon);
		
	if(is_null($result)){
		echo json_encode(false);
	}else{
		$offer = array();
		$offer[OfferResponse::ID] = $result;
		$offer[OfferResponse::USER_ID] = $user_id;
		$offer[OfferResponse::PRIM] = $prim;
		$offer[OfferResponse::KOMISYON] = $komisyon;
		$offer[OfferResponse::PROD_KOMISYON] = $prod_komisyon;
		$offer[OfferResponse::COMPANY_ID] = $company_id;
		
		echo json_encode($offer);
	}
}

?>