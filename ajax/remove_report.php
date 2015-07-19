<?php
include_once (__DIR__.'/../Util/init.php');
include_once (__DIR__.'/../service/UserReportService.php');

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
	
	$report_id = Util::cleanInput($_POST['report_id']);
	
	$userReportService = new UserReportService();
	$result = $userReportService->remove($report_id);
	
	echo json_encode($result);
}

?>