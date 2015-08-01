<!-- PERSONEL -->
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
	include_once (__DIR__.'/../files/FileUploader.php');
	
	$offerId = null;
	if(isset($_GET['offer_id'])){
		$offerId = Util::cleanInput($_GET['offer_id']);
	}
	if(empty($offerId)){
		Util::redirect("/positive/error/404.php");
	}
	
	$offerService = new OfferService();
	$policyReqDetail = $offerService->getPolicyRequest($offerId, null);
	if(empty($policyReqDetail)){
		Util::redirect("/positive/error/404.php");
	}
	
	if(!empty($_POST)){
		$request_id = Util::cleanInput($_POST['request_id']);
		$offer_id = Util::cleanInput($_POST['offer_id']);
		$card_id = Util::cleanInput($_POST['card_id']);
		$policy_number = Util::cleanInput($_POST['policy_number']);
		$policy_ek_bilgi = Util::cleanInput($_POST['policeEkBilgi']);
		
		$fileUploader = new FileUploader();
		$policyPath = $fileUploader->uploadPolicy($offer_id, $_FILES['policyFile']);
		$makbuzPath = $fileUploader->uploadMakbuz($offer_id, $_FILES['makbuzFile']);
		
		if(is_null($policyPath) || is_null($makbuzPath)){
			?>
			<div align="center">
				<div class="alert alert-danger" role="alert">Dosyalar yüklenemedi, bir hata ile karşılaşıldı!</div>
			</div>
			<?php
		}else{
			$result = $offerService->addPolicy($request_id, $offer_id, $card_id, $policyPath, $makbuzPath, $user[User::ID], $policy_number, $policy_ek_bilgi);
			if(is_null($result)){
				?>
				<div align="center">
					<div class="alert alert-danger" role="alert">Poliçe onaylanamadı, bir hata ile karşılaşıldı!</div>
				</div>
				<?php
			}else{
				Util::redirect("/positive/personel/completedPolicies.php");
			}
		}
	}
?>
<script src="/positive/js/policyReq.js"></script>
<div class="container offer-request-screen">
	<div class="offers-column">
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td>Talep No</td>
					<td>Acenta</td>
					<td>Poliçe</td>
					<td>İstek tarihi</td>
					<?php if($policyReqDetail[PolicyRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
						<td>Plaka</td>
						<td>TC Kimlik No</td>
						<td>Vergi No</td>
						<td>Belge No</td>
						<td>ASBİS No</td>
					<?php } ?>
					<td>Ek Bilgi</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $policyReqDetail[PolicyRequest::REQUEST_ID];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::BRANCH_NAME];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::POLICY_TYPE];?></td>
					<td><?php echo DateUtil::format($policyReqDetail[PolicyRequest::REQUEST_DATE]);?></td>
					<?php if($policyReqDetail[PolicyRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
						<td><?php echo $policyReqDetail[PolicyRequest::PLAKA];?></td>
						<td><?php echo $policyReqDetail[PolicyRequest::TCKN];?></td>
						<td><?php echo $policyReqDetail[PolicyRequest::VERGI];?></td>
						<td><?php echo $policyReqDetail[PolicyRequest::BELGE];?></td>
						<td><?php echo $policyReqDetail[PolicyRequest::ASBIS];?></td>
					<?php } ?>
					<td><?php echo $policyReqDetail[PolicyRequest::EK_BILGI];?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td>Teklif No</td>
					<td>Teknikçi</td>
					<td>Teklif tarihi</td>
					<td>Şirket</td>
					<td>Prim</td>
					<td>Komisyon</td>
					<td>Prod Komisyonu</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $policyReqDetail[PolicyRequest::OFFER_ID];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::PERSONEL_NAME];?></td>
					<td><?php echo DateUtil::format($policyReqDetail[PolicyRequest::OFFER_DATE]);?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::COMPANY_NAME];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::PRIM];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::KOMISYON];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::PROD_KOMISYON];?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td>Kart üzerindeki isim</td>
					<td>Kart numarası</td>
					<td>Son kullanma tarihi</td>
					<td>Cvc Kodu</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $policyReqDetail[PolicyRequest::CARD_NAME];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::CARD_NO];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::EXPIRE_DATE];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::CVC_CODE];?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<form action="" method="post" autocomplete="off" class="form-signin" id="policy_complete_form" enctype="multipart/form-data">
			<input type="hidden" id="request_id" name="request_id" value='<?php echo $policyReqDetail[PolicyRequest::REQUEST_ID];?>'>
			<input type="hidden" id="offer_id" name="offer_id" value='<?php echo $policyReqDetail[PolicyRequest::OFFER_ID];?>'>
			<input type="hidden" id="card_id" name="card_id" value='<?php echo $policyReqDetail[PolicyRequest::CARD_ID];?>'>
			<label class="login-error" id="policy-number-error"></label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Poliçe numarası</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="policy_number" name="policy_number">
			</div>
			<label class="login-error" id="policy-file-error"></label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Poliçe Dosyası</span>
				<input type="file" class="form-control" aria-describedby="basic-addon1" id="policyFile" name="policyFile">
			</div>
			<label class="login-error" id="makbuz-file-error"></label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Makbuz Dosyası</span>
				<input type="file" class="form-control" aria-describedby="basic-addon1" id="makbuzFile" name="makbuzFile">
			</div>
			<label class="login-error" id="ek-bilgi-error"></label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Ek Bilgi</span>
				<textarea rows="4" class="form-control" aria-describedby="basic-addon1" id="policeEkBilgi" name="policeEkBilgi"></textarea>
			</div>
			<br>
			<button class="btn btn-lg btn-primary btn-block" type="button" onclick='validatePolicy()' id="offer-request-button">Poliçeyi onayla</button>
		</form>
	</div>
	<div class="well chat-column">
		<?php $chat_request_id = $policyReqDetail[PolicyRequest::REQUEST_ID];?>
		<?php include_once (__DIR__.'/../chat.php'); ?>
	</div>
</div>
<script type="text/javascript">
	$('#personel_2').addClass("active");
</script>
</body>