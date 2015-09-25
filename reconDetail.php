<!-- BRANCH -->
<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/Util/init.php');
	include_once (__DIR__.'/classes/Recon.php');
	if($loggedIn){
		$user = Session::get(Session::USER);
	}
	include_once (__DIR__.'/navigationBar.php');
	
	if(isset($_GET['recon_id'])){
		$takip_no = urlencode($_GET['recon_id']);
	}
	if(empty($takip_no)){
		Util::redirect('/positive/error/404.php');
	}
	
	$reconService = new ReconService();
	$reconDetail = $reconService->getRecon($takip_no, $user[User::ID], $user[User::ROLE]);
	if(is_null($reconDetail)){
		Util::redirect('/positive/error/403.php');
	}
	
	print_r($reconDetail);
?>
	<script src="/positive/js/recon.js"></script>
	<script type="text/javascript">
		$('#recon_1').addClass("active");
	</script>
</body>