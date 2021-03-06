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
		if($user[User::ROLE] != User::BRANCH){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
		Util::redirect("/positive/profile.php");
	}
	
	if(isset($_GET['policy_id'])){
		$policy_id = urlencode($_GET['policy_id']);
	}
	if(empty($policy_id)){
		Util::redirect('/positive/error/404.php');
	}
	
	$offerService = new OfferService();
	$policy = $offerService->getCompletedPolicy($policy_id, $user[User::ID]);
	if(empty($policy)){
		Util::redirect('/positive/error/404.php');
	}
?>
<div class="container">
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Talep No</td>
				<td>Acenta</td>
				<td>İstek tarihi</td>
				<?php if($policy[PolicyRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
					<td>Plaka</td>
					<td>TC Kimlik No</td>
					<td>Vergi No</td>
					<td>Belge No</td>
					<td>ASBİS</td>
					<td>Marka Kodu</td>
				<?php } ?>
				<td>Ek Bilgi</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $policy[Policy::REQUEST_ID];?></td>
				<td><?php echo $policy[Policy::BRANCH_NAME];?></td>
				<td><?php echo DateUtil::format($policy[Policy::REQUEST_DATE]);?></td>
				<?php if($policy[PolicyRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
					<td><?php echo $policy[Policy::PLAKA];?></td>
					<td><?php echo $policy[Policy::TCKN];?></td>
					<td><?php echo $policy[Policy::VERGI];?></td>
					<td><?php echo $policy[Policy::BELGE];?></td>
					<td><?php echo $policy[Policy::ASBIS];?></td>
					<td><?php echo $policy[Policy::MARKA_KODU];?></td>
				<?php } ?>
				<td><?php echo $policy[Policy::EK_BILGI];?></td>
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
				<td><?php echo $policy[Policy::OFFER_ID];?></td>
				<td><?php echo $policy[Policy::PERSONEL_NAME];?></td>
				<td><?php echo DateUtil::format($policy[Policy::OFFER_DATE]);?></td>
				<td><?php echo $policy[Policy::COMPANY_NAME];?></td>
				<td><?php echo $policy[Policy::PRIM];?></td>
				<td><?php echo $policy[Policy::KOMISYON];?></td>
				<td><?php echo $policy[Policy::PROD_KOMISYON];?></td>
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
				<td><?php echo $policy[Policy::CARD_NAME];?></td>
				<td><?php echo $policy[Policy::CARD_NO];?></td>
				<td><?php echo $policy[Policy::EXPIRE_DATE];?></td>
				<td><?php echo $policy[Policy::CVC_CODE];?></td>
			</tr>
		</tbody>
	</table>
	<br>
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Poliçe id</td>
				<td>Poliçe no</td>
				<td>Poliçe türü</td>
				<td>Poliçe tarihi</td>
				<td>Poliçeyi yapan</td>
				<td>Poliçe Dosyası</td>
				<td>Makbuz Dosyası</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $policy[Policy::POLICY_ID];?></td>
				<td><?php echo $policy[Policy::POLICY_NUMBER];?></td>
				<td><?php echo $policy[Policy::POLICY_TYPE];?></td>
				<td><?php echo $policy[Policy::POLICY_COMPLETE_DATE];?></td>
				<td><?php echo $policy[Policy::POLICY_COMPLETE_PERSONEL];?></td>
				<td><a target="_blank" href="/positive/download.php?id=<?php echo $policy[Policy::POLICY_ID];?>&type=policy"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>İndir</a></td>
				<td><a target="_blank" href="/positive/download.php?id=<?php echo $policy[Policy::POLICY_ID];?>&type=makbuz"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>İndir</a></td>
			</tr>
		</tbody>
	</table>
	<br>
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Poliçe Ek Bilgi</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $policy[Policy::POLICE_EK_BILGI];?></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	$('#branch_4').addClass("active");
</script>
</body>