<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}
	
	$request_id = Util::cleanInput($_POST['request_id']);
	
	$offerService = new OfferService();
	$result = $offerService->openRequest($request_id);
	
	echo json_encode($result);
}

?>