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
	
	$offerService = new OfferService();
	//bu kullanıcının poliçe isteği yapılmış ancak poliçeleşmemiş isteklerini getir
	$allPolicyRequests = $offerService->getAllPolicyRequest();
	if(empty($allPolicyRequests)){
	?>
			<div id="user_table_msg" align="center">
				<div class="alert alert-warning" role="alert">Hiç poliçe isteğiniz bulunmamaktadır.</div>
			</div>
		<?php
	}
?>
<div class="container">
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Talep No</b></td>
					<td><b>Teklif No</b></td>
					<td><b>Talep eden</b></td>
					<td><b>Teklif tarihi</b></td>
					<td><b>Plaka</b></td>
					<td><b>Şirket</b></td>
					<td><b>Prim</b></td>
					<td><b>Komisyon</b></td>
					<td><b>Prod Komisyonu</b></td>
					<td><b>Aç</b></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($allPolicyRequests as $policyRequest){ ?>
				<tr>
					<td><b><?php echo $policyRequest[PolicyRequest::REQUEST_ID]; ?></b></td>
					<td><b><?php echo $policyRequest[PolicyRequest::OFFER_ID]; ?></b></td>
					<td><?php echo $policyRequest[PolicyRequest::BRANCH_NAME]; ?></td>
					<td><?php echo DateUtil::format($policyRequest[PolicyRequest::OFFER_DATE]); ?></td>
					<td><?php echo $policyRequest[PolicyRequest::PLAKA]; ?></td>
					<td><?php echo $policyRequest[PolicyRequest::COMPANY_NAME]; ?></td>
					<td><?php echo $policyRequest[PolicyRequest::PRIM]; ?></td>
					<td><?php echo $policyRequest[PolicyRequest::KOMISYON]; ?></td>
					<td><?php echo $policyRequest[PolicyRequest::PROD_KOMISYON]; ?></td>
					<td>
						<button id="open_policy_req_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/personel/policyReqDetails.php?offer_id=<?php echo $policyRequest[PolicyRequest::OFFER_ID];?>'">
						  <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
						</button>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$('#personel_2').addClass("active");
</script>
</body>