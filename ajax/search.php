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
	}
	
	$searchService = new SearchService();
	
	$type = Util::cleanInput($_POST['type']);
	$response = null;
	if($type == 1){//request id search
		$request_id = Util::cleanInput($_POST['request_id']);
		$response = $searchService->searchRequest($request_id, $user[User::ROLE], $user[User::ID]);
	}
	if($type == 2){//offer id search
		$offer_id = Util::cleanInput($_POST['offer_id']);
		$response = $searchService->searchOffer($offer_id, $user[User::ROLE], $user[User::ID]);
	}
	if($type == 3){//policy no search
		$policy_id = Util::cleanInput($_POST['policy_id']);
		$response = $searchService->searchPolicy($policy_id, $user[User::ROLE], $user[User::ID]);
	}
	if($type == 4){//extended search
		$plaka_no = Util::cleanInput($_POST['plaka_no']);
		$tckn = Util::cleanInput($_POST['tckn']);
		$vergi_no = Util::cleanInput($_POST['vergi_no']);
		$belge_no = Util::cleanInput($_POST['belge_no']);
		$asbis = Util::cleanInput($_POST['asbis']);
		$response = $searchService->searchExtend($plaka_no, $tckn, $vergi_no, $belge_no, $asbis, $user[User::ROLE], $user[User::ID]);
	}
	
	echo json_encode($response);
}

?>