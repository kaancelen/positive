<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}else{
		$user = Session::get(Session::USER);
		if($user[User::ROLE] != User::ADMIN){
			$logger->write(ALogger::INFO, __FILE__, "Request not from admin");
			echo json_encode(false);
			return;
		}
	}
	
	$user_id = Util::cleanInput($_POST['user_id']);
	$makeActive = Util::cleanInput($_POST['make_active']);
	$logger->write(ALogger::INFO, __FILE__, "User remove request [".$user_id."] from [".$user[User::CODE]."]");
	
	$userService = new UserService();
	$result = $userService->toggleUserActivity($user_id, $makeActive);
	if($result){
		echo json_encode(true);
	}else{
		echo json_encode(false);
	}
}

?>