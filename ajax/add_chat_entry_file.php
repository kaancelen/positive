<?php
include_once (__DIR__.'/../Util/init.php');
include_once (__DIR__.'/../service/ChatService.php');
include_once (__DIR__.'/../classes/chat.php');
include_once (__DIR__.'/../files/FileUploader.php');

if(!empty($_POST) && !empty($_FILES)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}
	
	$file = $_FILES[0];
	if(isset($_FILES[0]) && $_FILES[0]['error'] == 0){
		$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));//get extension
		$dest_name = uniqid().".".$ext;
		$url_name = '/positive/files/chat/'.$dest_name;
		
		$fileUploader = new FileUploader();
		$uploadResult = $fileUploader->uploadChatFile($dest_name, $_FILES[0]);
		if(!is_null($uploadResult)){//continue process
			$user = Session::get(Session::USER);
			$request_id = Util::cleanInput($_POST['request_id']);
			$text = "<a href='".$url_name."' target='_blank'>".$dest_name."</a>";
			
			$chatService = new ChatService();
			$result = $chatService->addEntry($request_id, $user[User::ID], $user[User::NAME], $text);
			
			if($result){
				echo json_encode(array('text' => $text, 'user_name' => $user[User::NAME]));
			}else{
				echo json_encode(true);
			}
		}
	}
}
?>