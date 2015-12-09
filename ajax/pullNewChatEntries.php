<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}

	$user = Session::get(Session::USER);
	$request_id_string = Util::cleanInput($_POST['request_id_string']);
	$page_type = Util::cleanInput($_POST['page_type']);
	
	$genericService = new GenericService();
	$result = $genericService->getRequestIdsOfNewChatEntries($user[User::ID], $request_id_string, $page_type);
	echo json_encode($result);
}
?>