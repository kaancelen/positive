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

	$offer_id = Util::cleanInput($_POST['offer_id']);
	$prim = Util::cleanInput($_POST['prim']);
	$komisyon = Util::cleanInput($_POST['komisyon']);
	$prod_komisyon = Util::cleanInput($_POST['prod_komisyon']);
	$ust_komisyon = Util::cleanInput($_POST['ust_komisyon']);
	$bagli_komisyon = Util::cleanInput($_POST['bagli_komisyon']);
	
	$prim = str_replace(".", "", $prim);
	$prim = str_replace(",", ".", $prim);
	$prim = floatval($prim);
	
	$komisyon = str_replace(".", "", $komisyon);
	$komisyon = str_replace(",", ".", $komisyon);
	$komisyon = floatval($komisyon);
	
	$prod_komisyon = str_replace(".", "", $prod_komisyon);
	$prod_komisyon = str_replace(",", ".", $prod_komisyon);
	$prod_komisyon = floatval($prod_komisyon);
	
	$ust_komisyon = str_replace(".", "", $ust_komisyon);
	$ust_komisyon = str_replace(",", ".", $ust_komisyon);
	$ust_komisyon = floatval($ust_komisyon);
	
	$bagli_komisyon = str_replace(".", "", $bagli_komisyon);
	$bagli_komisyon = str_replace(",", ".", $bagli_komisyon);
	$bagli_komisyon = floatval($bagli_komisyon);
	
	$offerService = new OfferService();
	$result = $offerService->changeOffer($offer_id, $prim, $komisyon, $prod_komisyon, $ust_komisyon, $bagli_komisyon);
		
	echo json_encode($result);
}

?>