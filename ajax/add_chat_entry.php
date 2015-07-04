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
	$user = Session::get(Session::USER);
	
	$request_id = Util::cleanInput($_POST['request_id']);
	$text = Util::cleanInput($_POST['text']);
	
	$chatService = new ChatService();
	$result = $chatService->addEntry($request_id, $user[User::ID], $user[User::NAME], $text);
	
	if($result){
		echo json_encode(array('user_name' => $user[User::NAME]));
	}else{
		echo json_encode(false);
	}
	
}
?>