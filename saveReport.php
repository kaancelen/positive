<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
<script type="text/javascript">
	$('#report_1').addClass("active");
</script>
<?php 
	include_once (__DIR__.'/Util/init.php');
	include_once (__DIR__.'/navigationBar.php');
	include_once (__DIR__.'/service/UserReportService.php');
	include_once (__DIR__.'/files/FileUploader.php');
	
	$user = Session::get(Session::USER);
	
	if(!empty($_POST)){
		$subject = Util::cleanInput($_POST['subject']);
		$content = Util::cleanInput($_POST['content']);
		
		$fileUploader = new FileUploader();
		$ss1_path = null;
		if(isset($_FILES['ss1']) && $_FILES['ss1']['error'] == 0){
			$path1 = $_FILES['ss1']['name'];
			$ss1_ext = strtolower(pathinfo($path1, PATHINFO_EXTENSION));
			$ss1_name = sprintf("%s.%s", uniqid(), $ss1_ext);
			$ss1_path = $fileUploader->uploadImage($ss1_name, $_FILES['ss1']);
			if(is_null($ss1_path)){
				?>
				<div align="center">
					<div class="alert alert-warn" role="alert"><?php echo $_FILES['ss1']['name']; ?> yüklenemedi, bir hata ile karşılaşıldı!</div>
				</div>
				<?php
				return;
			}
		}
		$ss2_path = null;
		if(isset($_FILES['ss2']) && $_FILES['ss2']['error'] == 0){
			$path2 = $_FILES['ss2']['name'];
			$ss2_ext = strtolower(pathinfo($path2, PATHINFO_EXTENSION));
			$ss2_name = sprintf("%s.%s", uniqid(), $ss2_ext);
			$ss2_path = $fileUploader->uploadImage($ss2_name, $_FILES['ss2']);
			if(is_null($ss2_path)){
				?>
				<div align="center">
					<div class="alert alert-warn" role="alert"><?php echo $_FILES['ss2']['name']; ?> yüklenemedi, bir hata ile karşılaşıldı!</div>
				</div>
				<?php
				return;
			}
		}
		
		$userReportService = new UserReportService();
		$result = $userReportService->createUserReport($user[User::ID], $subject, $content, $ss1_path, $ss2_path);
		if($result == -1){
			?>
			<div align="center">
				<div class="alert alert-warn" role="alert">Hata kaydedilemedi, bir hata ile karşılaşıldı!</div>
			</div>
			<?php
		}else{
			?>
			<div align="center">
				<div class="alert alert-success" role="alert">Hata başarı ile kaydedildi, bize yardımcı olduğunuz için çok teşekkürler.</div>
			</div>
			<?php
		}
	}
?>
</body>