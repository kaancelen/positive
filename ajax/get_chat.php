<?php
include_once (__DIR__.'/../Util/init.php');
include_once (__DIR__.'/../service/ChatService.php');
include_once (__DIR__.'/../classes/chat.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}
	
	$request_id = Util::cleanInput($_POST['request_id']);
	
	$chatService = new ChatService();
	$allChat = $chatService->getEntries($request_id);
	
	echo json_encode($allChat);
}
?>