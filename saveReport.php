<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/Util/init.php');
	include_once (__DIR__.'/navigationBar.php');
	include_once (__DIR__.'/service/UserReportService.php');
	
	if(!empty($_POST)){
		$subject = Util::cleanInput($_POST['subject']);
		$content = Util::cleanInput($_POST['content']);
		
		$userReportService = new UserReportService();
		$result = $userReportService->createUserReport($subject, $content, null, null);
		echo $result;
	}
?>
<script type="text/javascript">
	$('#report_1').addClass("active");
</script>
</body>