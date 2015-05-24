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
	$username = Util::cleanInput($_POST['username']);
	$logger->write(ALogger::INFO, __FILE__, "User reset password request [".$user_id.", ".$username."] from [".$user[User::CODE]."]");
	//new password
	$salt = Hash::unique();
	$hash = Hash::make($username, $salt);
	
	$userService = new UserService();
	$result = $userService->changePassword($user_id, $salt, $hash);
	if($result){
		echo json_encode(true);
	}else{
		echo json_encode(false);
	}
}

?>