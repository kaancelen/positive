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
	
	$offerService = new OfferService();
	$allPolicyRequests = $offerService->getAllPolicyRequest($user[User::ID]);
	
	$cancelService = new CancelService();
	$allCancelRequests = $cancelService->getAllCancelRequests($user[User::ID]);
?>
<script src="/positive/js/policiesPage.js"></script>
<div class="container">
	<div class="policy_tabs">
		<ol class="nav nav-pills">
		  <li id="policy_tabs_uretim" role="presentation" class="active"><a href="#" onclick="policyTabChange(0);">Üretim</a></li>
		  <li id="policy_tabs_iptal" role="presentation"><a href="#" onclick="policyTabChange(1);">İptal</a></li>
		</ol>
	</div>
	<div id="policy_req_table">
		<?php if(empty($allPolicyRequests)){ ?>
			<div id="user_table_msg" align="center">
				<div class="alert alert-warning" role="alert">Hiç poliçe isteğiniz bulunmamaktadır.</div>
			</div>
		<?php } ?>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<td><b>Talep No</b></td>
						<td><b>Teklif No</b></td>
						<td><b>Teklif Veren</b></td>
						<td><b>Poliçe</b></td>
						<td><b>Teklif Tarihi</b></td>
						<td><b>Plaka</b></td>
						<td><b>Şirket</b></td>
						<td><b>Aç</b></td>
					</tr>
					<tr>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($allPolicyRequests as $policyRequest){ ?>
					<tr <?php if($policyRequest[PolicyRequest::STATUS] == 3) echo "class='row-offer-cancelled'";?>>
						<td><b><?php echo $policyRequest[PolicyRequest::REQUEST_ID]; ?></b></td>
						<td><b><?php echo $policyRequest[PolicyRequest::OFFER_ID]; ?></b></td>
						<td><?php echo $policyRequest[PolicyRequest::PERSONEL_NAME]; ?></td>
						<td><?php echo $policyRequest[PolicyRequest::POLICY_TYPE]; ?></td>
						<td><?php echo DateUtil::format($policyRequest[PolicyRequest::OFFER_DATE]); ?></td>
						<td><?php echo $policyRequest[PolicyRequest::PLAKA]; ?></td>
						<td><?php echo $policyRequest[PolicyRequest::COMPANY_NAME]; ?></td>
						<td>
							<button id="open_policy_req_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
								onclick="location.href = '/positive/branch/policyReqDetails.php?offer_id=<?php echo $policyRequest[PolicyRequest::OFFER_ID];?>'">
							  <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div id="cancel_req_table" style="display: none">
		<?php if(empty($allCancelRequests)){ ?>
			<div id="user_table_msg" align="center">
				<div class="alert alert-warning" role="alert">Hiç poliçe iptal isteğiniz bulunmamaktadır.</div>
			</div>
		<?php } ?>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<td><b>Talep No</b></td>
						<td><b>İşlem Yapan</b></td>
						<td><b>Poliçe</b></td>
						<td><b>Giriş Tarihi</b></td>
						<td><b>Poliçe No</b></td>
						<td><b>Şirket</b></td>
						<td><b>Aç</b></td>
					</tr>
					<tr>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($allCancelRequests as $cancelRequest){ ?>
					<?php 
						$class = "";
						if($cancelRequest[CancelRequest::STATUS] == 1){
							$class = "row-offer-completed";
						}else if($cancelRequest[CancelRequest::STATUS] == 2){
							$class = "row-offer-cancelled";
						}
					?>
					<tr <?php echo "class=".$class;?>>
						<td><b><?php echo $cancelRequest[CancelRequest::ID]; ?></b></td>
						<td><b><?php echo $cancelRequest[CancelRequest::PERSONEL_NAME]; ?></b></td>
						<td><?php echo $cancelRequest[CancelRequest::POLICY_TYPE]; ?></td>
						<td><?php echo DateUtil::format($cancelRequest[CancelRequest::CREATION_DATE]); ?></td>
						<td><?php echo $cancelRequest[CancelRequest::POLICY_NUMBER]; ?></td>
						<td><?php echo $cancelRequest[CancelRequest::COMPANY_NAME]; ?></td>
						<td>
							<button id="open_cancel_req_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
								onclick="location.href = '/positive/branch/cancelReqDetails.php?cancel_id=<?php echo $cancelRequest[CancelRequest::ID];?>'">
							  <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#branch_3').addClass("active");
</script>
</body>