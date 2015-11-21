<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}else{
		$user = Session::get(Session::USER);
		if($user[User::ROLE] != User::BRANCH && $user[User::ROLE] != User::PERSONEL){
			echo json_encode(false);
			return;
		}
	}
	
	$request_id = Util::cleanInput($_POST['request_id']);
	$type = Util::cleanInput($_POST['type']);
	if($type != PolicyType::DIGER){
		$plaka = Util::cleanInput($_POST['plaka']);
		$tckn = Util::cleanInput($_POST['tckn']);
		$vergi = Util::cleanInput($_POST['vergi']);
		$belge = Util::cleanInput($_POST['belge']);
		$asbis = Util::cleanInput($_POST['asbis']);
		$marka_kodu = Util::cleanInput($_POST['marka_kodu']);
	}
	$description = Util::cleanInput($_POST['description']);
	
	$offerService = new OfferService();
	if($type != PolicyType::DIGER){
		$result = $offerService->updateRequest($request_id, $description, $plaka, $tckn, $vergi, $belge, $asbis, $marka_kodu);
	}else{
		$result = $offerService->updateRequest($request_id, $description);
	}
	
	echo json_encode($result);
}
?>