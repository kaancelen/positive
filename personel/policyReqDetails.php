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
					<td><?php echo DateUtil::format($policyReqDetail[PolicyRequest::REQUEST_DATE]);?></td>
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
					<td><?php echo DateUtil::format($policyReqDetail[PolicyRequest::OFFER_DATE]);?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::COMPANY_NAME];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::PRIM];?></td>
					<td><?php echo $policyReqDetail[PolicyRequest::KOMISYON];?></td>
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
		Burada dosyaları yüklemek için file upload olsun <br>
		bu dosyayı yükleyince poliçe yapıldı olsun.
	</div>
	<div class="well chat-column">
		<h4 style="text-align:center">Konuşma</h4>
		<hr>	
	</div>
</div>
<script type="text/javascript">
	$('#personel_2').addClass("active");
</script>
</body>