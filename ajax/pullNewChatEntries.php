<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}

	$user = Session::get(Session::USER);
	
	$genericService = new GenericService();
	$result = $genericService->getRequestIdsOfNewChatEntries($user[User::ID]);
	echo json_encode($result);
}
?>