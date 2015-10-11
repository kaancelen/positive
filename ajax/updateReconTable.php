<?php
include_once (__DIR__.'/../Util/init.php');
include_once (__DIR__.'/../service/ReconService.php');

if(!empty($_POST)){
	if(!$loggedIn){
		$logger->write(ALogger::INFO, __FILE__, "Request without login!");
		echo json_encode(false);
		return;
	}
	
	$user = Session::get(Session::USER);
	
	$month = Util::cleanInput($_POST['month']);
	$year = Util::cleanInput($_POST['year']);
	$user_id = $user[User::ID];
	$user_role = $user[User::ROLE];

	$reconService = new ReconService();
	$reconPolicies = $reconService->getPoliciesInMonth($month, $year, $user_id, $user_role);
	$reconCancelPolicies = $reconService->getPolicyCancelsInMonth($month, $year, $user_id, $user_role);
	
	foreach ($reconPolicies as $reconPolicy){
		$reconService->insertRecon($reconPolicy);
	}
	foreach ($reconCancelPolicies as $reconCancel){
		$reconService->insertReconCancel($reconCancel);
	}
	
	echo json_encode(true);
}
?>