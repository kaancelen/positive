<!-- ADMIN -->
<head>
<?php 
	include_once(__DIR__.'/../head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/../Util/init.php');
	include_once (__DIR__.'/../service/UserReportService.php');
	include_once (__DIR__.'/../classes/userReport.php');
	if($loggedIn){
		$user = Session::get(Session::USER);
		if($user[User::ROLE] != User::ADMIN){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');

	if(!empty($_GET['report_id'])){
		$report_id = Util::cleanInput(urldecode($_GET['report_id']));
		$userReportService = new UserReportService();
		
		if(!empty($_POST)){
			$feedback = Util::cleanInput($_POST['feedback']);
			if(isset($_POST['statu0'])){
				$statu = 0;
			}else if(isset($_POST['statu1'])){
				$statu = 1;
			}else if(isset($_POST['statu2'])){
				$statu = 2;
			}
	
			$response = $userReportService->update($report_id, $statu, $feedback);
			if($response){
				?>
				<div id="user_form_msg" align="center">
					<div class="alert alert-success" role="alert">Rapor başarıyla güncellendi.</div>
				</div>
				<?php
			}else{
				?>
				<div id="user_form_msg" align="center">
					<div class="alert alert-danger" role="alert">Rapor güncellenirken hata oluştu!</div>
				</div>
				<?php
			}
		}
		
		$report = $userReportService->get($report_id);
		
		if(!empty($report[UserReport::FILE1])){
			$file1_media_url = "/positive/files/report_images/".substr($report[UserReport::FILE1], strrpos($report[UserReport::FILE1], '/') + 1);
		}
		if(!empty($report[UserReport::FILE2])){
			$file2_media_url = "/positive/files/report_images/".substr($report[UserReport::FILE2], strrpos($report[UserReport::FILE2], '/') + 1);
		}
	}else{
		Util::redirect("/positive/error/404.php");
	}
	
?>
<script src="/positive/js/report.js"></script>
<div class="container">
	<div class="left-column">
		<div style="height: 50%">
			<table class="offer-request-info-table">
				<thead>
					<tr>
						<td><b>ID</b></td>
						<td><b>Durum</b></td>
						<td><b>Başlık</b></td>
						<td><b>Rapor eden</b></td>
						<td><b>Kullanıcı id</b></td>
						<td><b>Rapor tarihi</b></td>
					</tr>
				</thead>
				<tbody>
					<?php 
						switch ($report[UserReport::STATUS]){
							case 1: $image = "/positive/images/yellow.png";break;
							case 2: $image = "/positive/images/green.png";break;
							case 0: 
							default: $image = "/positive/images/red.png";break; 
						}
					?>
				
					<tr id="report_<?php echo $report[UserReport::ID]; ?>">
						<td><b><?php echo $report[UserReport::ID]; ?></b></td>
						<td><img src="<?php echo $image; ?>"> </td>
						<td><?php echo $report[UserReport::SUBJECT]; ?></td>
						<td><?php echo $report[UserReport::USER_NAME]; ?></td>
						<td><?php echo $report[UserReport::USER_ID]; ?></td>
						<td><?php echo DateUtil::format($report[UserReport::CREATION_DATE]); ?></td>
					</tr>
				</tbody>
			</table>
			<br>
			<div class="content">
				<p><?php echo $report[UserReport::CONTENT];?></p>
			</div>
		</div>
		<div style="height: 50%">
			<form class="form-signin" id="feedback-form" action="" method="post" autocomplete="off">
				<label for="feedback" class="login-error" id="feedback-error"></label>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Feedback</span>
					<textarea rows="8" class="form-control" aria-describedby="basic-addon1" id="feedback" name="feedback"><?php echo $report[UserReport::FEEDBACK];?></textarea>
				</div>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">
						<input type="radio" id="statu0" name="statu0" onchange="on_statu0_change()">
						<img src="/positive/images/red.png">
					</span>
					<span class="input-group-addon" id="basic-addon1">
						<input type="radio" id="statu1" name="statu1" onchange="on_statu1_change()">
						<img src="/positive/images/yellow.png">
					</span>
					<span class="input-group-addon" id="basic-addon1">
						<input type="radio" id="statu2" name="statu2" onchange="on_statu2_change()">
						<img src="/positive/images/green.png">
					</span>
				</div>
				<button class="btn btn-lg btn-primary btn-block" type="button" onclick="validateFeedback()" id="update-report">Güncelle</button>
			</form>
		</div>
	</div>
	<div class="right-column">
		<div style="height: 50%">
			<?php if(isset($file1_media_url)){?>
			<img src="<?php echo $file1_media_url; ?>" width="100%" height="100%">
			<?php } ?>
		</div>
		<div style="height: 50%">
			<?php if(isset($file2_media_url)){?>
			<img src="<?php echo $file2_media_url; ?>" width="100%" height="100%">
			<?php } ?>
		</div>
	</div>
</div>
<?php
$radio_check = "";
switch ($report[UserReport::STATUS]){
	case 1: $radio_check = "$('#statu1').prop('checked', true);";break;
	case 2: $radio_check = "$('#statu2').prop('checked', true);";break;
	case 0:
	default: $radio_check = "$('#statu0').prop('checked', true);";break;
}
?>
<script type="text/javascript">
	<?php echo $radio_check; ?>
	$('#admin_2').addClass("active");
</script>
<body>