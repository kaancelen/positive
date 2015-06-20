<!-- BRANCH -->
<head>
<?php 
	include_once(__DIR__.'/../head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/../Util/init.php');
	if($loggedIn){
		$user = Session::get(Session::USER);
		if($user[User::ROLE] != User::PERSONEL){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	
	$offerId = null;
	if(isset($_GET['offer_id'])){
		$offerId = Util::cleanInput($_GET['offer_id']);
	}
	if(empty($offerId)){
		Util::redirect("/positive/error/404.php");
	}
	
	$offerService = new OfferService();
	$policyReqDetail = $offerService->getPolicyRequest($offerId, null);
?>
</body>