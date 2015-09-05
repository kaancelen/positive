<!DOCTYPE html>
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
	
	$cancel_id = null;
	if(isset($_GET['cancel_id'])){
		$cancel_id = Util::cleanInput($_GET['cancel_id']);
	}
	if(empty($cancel_id)){
		Util::redirect("/positive/error/404.php");
	}
	
	$cancelService = new CancelService();
	$cancelReqDetail = $cancelService->getCancelRequest($cancel_id, null);
	if(empty($cancelReqDetail)){
		Util::redirect("/positive/error/404.php");
	}
?>
<script src="/positive/js/policiesPage.js"></script>
<div class="container offer-request-screen">
	<div class="offers-column">
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td>Talep No</td>
					<td>Giriş Tarihi</td>
					<td>Poliçe Türü</td>
					<td>Poliçe No</td>
					<td>Şirket</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $cancelReqDetail[CancelRequest::ID];?></td>
					<td><?php echo DateUtil::format($cancelReqDetail[CancelRequest::CREATION_DATE]);?></td>
					<td><?php echo $cancelReqDetail[CancelRequest::POLICY_TYPE];?></td>
					<td><?php echo $cancelReqDetail[CancelRequest::POLICY_NUMBER];?></td>
					<td><?php echo $cancelReqDetail[CancelRequest::COMPANY_NAME];?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td>İşlem yapan</td>
					<td>İşlem Tarihi</td>
					<td>Ek Bilgi</td>
					<td>Satış Sözleşme Eki</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $cancelReqDetail[CancelRequest::PERSONEL_NAME];?></td>
					<td><?php echo DateUtil::format($cancelReqDetail[CancelRequest::COMPLETE_DATE]);?></td>
					<td><?php echo $cancelReqDetail[CancelRequest::EK_BILGI];?></td>
					<td><a target="_blank" href="/positive/download.php?file=<?php echo $cancelReqDetail[CancelRequest::SOZLESME];?>"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>İndir</a></td>
				</tr>
			</tbody>
		</table>
		<br>
		<?php 
			$status = $cancelReqDetail[CancelRequest::STATUS];
			switch ($status) {
				case 0: 
					$message = "Poliçe iptali incelenmektedir.";
					$alert = "info";
					break;
				case 1: 
					$message = "Poliçe iptali onaylandı.";
					$alert = "success";
					break;
				case 2: 
					$message = "Poliçe iptali reddedildi.";
					$alert = "danger";
					break;
			}
		?>
		<?php if($status <= 2){?>
			<div id="user_table_msg" align="center">
				<div class="alert alert-<?php echo $alert; ?>" role="alert"><?php echo $message; ?></div>
			</div>
			<br>
			<?php if($status == 0){?>
			<div align="center">
				<button type="button" class="btn btn-default btn-lg" style="background-color: #00FF33" 
						onclick="cancelRequestOperation(<?php echo $cancelReqDetail[CancelRequest::ID];?>, 1);">
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> İşlemi tamamla
				</button>
				
				<button type="button" class="btn btn-default btn-lg" style="background-color: #FF3333" 
						onclick="cancelRequestOperation(<?php echo $cancelReqDetail[CancelRequest::ID];?>, 2);">
					<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Reddet
				</button>
			</div>
			<?php } ?>
		<?php } ?>
	</div>
	<div class="well chat-column">
		<?php $chat_request_id = (-1)*$cancelReqDetail[CancelRequest::ID]; ?>
		<?php include_once (__DIR__.'/../chat.php'); ?>
	</div>
</div>
<script type="text/javascript">
	$('#personel_2').addClass("active");
</script>
</body>