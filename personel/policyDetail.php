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
	
	if(isset($_GET['policy_id'])){
		$policy_id = urlencode($_GET['policy_id']);
	}
	if(empty($policy_id)){
		Util::redirect('/positive/error/404.php');
	}
	
	$offerService = new OfferService();
	$policy = $offerService->getCompletedPolicy($policy_id);
	if(empty($policy)){
		Util::redirect('/positive/error/404.php');
	}
	
	$userService = new UserService();
	$allAgents = $userService->allTypeOfUsers(User::BRANCH);
?>
<script src="/positive/js/policyDetail.js"></script>
<div class="container">
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Talep No</td>
				<td>Acenta&nbsp;<a href="#" data-toggle="modal" data-target=".bs-example-modal-sm"><span class="glyphicon glyphicon-edit"></span>Değiştir</a></td>
				<td>İstek tarihi</td>
				<?php if($policy[PolicyRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
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
				<td><?php echo $policy[Policy::REQUEST_ID];?></td>
				<td><?php echo $policy[Policy::BRANCH_NAME];?></td>
				<td><?php echo DateUtil::format($policy[Policy::REQUEST_DATE]);?></td>
				<?php if($policy[PolicyRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
					<td><?php echo $policy[Policy::PLAKA];?></td>
					<td><?php echo $policy[Policy::TCKN];?></td>
					<td><?php echo $policy[Policy::VERGI];?></td>
					<td><?php echo $policy[Policy::BELGE];?></td>
					<td><?php echo $policy[Policy::ASBIS];?></td>
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
				<td><a target="_blank" href="/positive/download.php?file=<?php echo $policy[Policy::POLICY_PATH];?>"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>İndir</a></td>
				<td><a target="_blank" href="/positive/download.php?file=<?php echo $policy[Policy::MAKBUZ_PATH];?>"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>İndir</a></td>
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
	$('#personel_3').addClass("active");
</script>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h2>Acenta değiştir</h2>
			</div>
			<div class="modal-body">
				<h4>Eski Acenta</h4>
				<?php echo $policy[Policy::BRANCH_NAME];?>
				<h4>Yeni Acenta</h4>
				<select id="new_agent" name="new_agent" class="form-control">
				<?php foreach ($allAgents as $agent){?>
					<option value="<?php echo $agent[User::ID]?>"><?php echo $agent[User::NAME]?> - <?php echo $agent[User::CODE]?></option>
				<?php }?>
				</select>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">İptal</button>
	        	<button type="button" class="btn btn-primary" onclick="onChangeAgentInfo(<?php echo $policy[Policy::REQUEST_ID];?>);">Kaydet</button>
			</div>
		</div>
	</div>
</div>
</body>