<?php
include_once (__DIR__.'/../Util/init.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}else{
		$user = Session::get(Session::USER);
		if($user[User::ROLE] != User::PERSONEL){
			$logger->write(ALogger::INFO, __FILE__, "Request not from personel");
			echo json_encode(false);
			return;
		}
	}
	
	$cancel_id = Util::cleanInput($_POST['cancel_id']);
	$status = Util::cleanInput($_POST['status']);
	$user_id = $user[User::ID];
	
	$cancelService = new CancelService();
	$result = $cancelService->requestOperation($cancel_id, $user_id, $status);
	
	echo json_encode($result);
}

?>