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
		if($user[User::ROLE] != User::BRANCH){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	
	if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
		Util::redirect("/positive/profile.php");
	}
	
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
<div class="container offer-request-screen">
	<div class="offers-column">
		<table class="offer-request-info-table">
			<thead>
				<tr>
					<td>Talep No</td>
					<td>Acenta</td>
					<td>İstek tarihi</td>
					<td>Plaka</td>
					<td>TC Kimlik No</td>
					<td>Vergi No</td>
					<td>Belge No</td>
					<td>ASBİS No</td>
					<td>Ek Bilgi</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $policyReqDetail[PolicyRequest::REQUEST_ID];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::BRANCH_NAME];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::REQUEST_DATE];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::PLAKA];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::TCKN];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::VERGI];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::BELGE];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::ASBIS];?></td>
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
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $policyReqDetail[PolicyRequest::OFFER_ID];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::PERSONEL_NAME];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::OFFER_DATE];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::COMPANY_NAME];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::PRIM];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::KOMISYON];?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<script src="/positive/js/policy.js"></script>
		<form class="form-signin" id="update-card-info-form" action="" method="post" autocomplete="off">
			<div class="well">
				<h2 class="form-signin-heading">Kredi kartı bilgileri</h2>
				<label class="login-error" id="card-name-error"></label>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Kart üzerindeki isim</span>
					<input type="text" class="form-control" aria-describedby="basic-addon1" id="name" name="name">
				</div>
				<br>
				<label class="login-error" id="card-no-error"></label>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Kart numarası</span>
					<input type="text" class="form-control" aria-describedby="basic-addon1" id="card" name="card">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Son kullanma tarihi</span>
					<select class="form-control" id="expireMonth" name="expireMonth">
						<?php for ($i = 1; $i <= 12; $i++){ ?>
					    	<option><?php echo $i; ?></option>
					    <?php } ?>
					</select>
					<select class="form-control" id="expireYear" name="expireYear">
						<?php for ($i = 2015; $i <= 2050; $i++){ ?>
					    	<option><?php echo $i; ?></option>
					    <?php } ?>
					</select>
				</div>
				<br>
				<label class="login-error" id="cvc-error"></label>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Cvc kodu</span>
					<input type="text" class="form-control" aria-describedby="basic-addon1" id="cvc" name="cvc">
				</div>
				<br>
				<button class="btn btn-lg btn-primary btn-block" type="button" onclick='editCardInfo()' id="policy-request-button">Poliçeleştir</button>
			</div>
		</form>
	</div>
	<div class="well chat-column">
		<h4 style="text-align:center">Konuşma</h4>
		<hr>	
	</div>
</div>
<script type="text/javascript">
	<?php $monthYear = explode("/", $policyReqDetail[PolicyRequest::EXPIRE_DATE]);?>
	$('#name').val('<?php echo $policyReqDetail[PolicyRequest::CARD_NAME];?>');
	$('#card').val('<?php echo $policyReqDetail[PolicyRequest::CARD_NO];?>');
	$('#cvc').val('<?php echo $policyReqDetail[PolicyRequest::CVC_CODE];?>');
	$('#expireMonth').val(<?php echo $monthYear[0]; ?>);
	$('#expireYear').val(<?php echo $monthYear[1]; ?>);
</script>
<script type="text/javascript">
	$('#branch_3').addClass("active");
</script>
</body>