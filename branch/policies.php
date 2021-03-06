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
		if($user[User::ROLE] != User::BRANCH){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	
	if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
		Util::redirect("/positive/profile.php");
	}
	
	if(isset($_GET['month']) && isset($_GET['year'])){
		$month = Util::cleanInput(urldecode($_GET['month']));
		$year = Util::cleanInput(urldecode($_GET['year']));
	}else{
		$month = date('n');
		$year = date('Y');
	}
	
	$offerService = new OfferService();
	//bu kullanıcının poliçe isteği yapılmış ancak poliçeleşmemiş isteklerini getir
	$allPolicyRequests = $offerService->getAllPolicyRequest($user[User::ID], $month, $year, null);

	if(empty($allPolicyRequests)){
	?>
		<div id="user_table_msg" align="center">
			<div class="alert alert-warning" role="alert">Hiç poliçe isteğiniz bulunmamaktadır.</div>
		</div>
	<?php
	}
	
	$request_id_list = array();
?>
<script src="/positive/js/pullNewChat.js"></script>
<script src="/positive/js/policiesPage.js"></script>
<div class="container">
	<?php $monthMap = Util::getMonthMap();?>
	<select id="month" name="month" class="form-control month-option">
		<?php foreach ($monthMap as $key => $value) {?>
		<option value="<?php echo $key;?>"><?php echo $value; ?></option>
		<?php }?>
	</select>
	<select id="year" name="year" class="form-control month-option">
		<?php for($i=2015; $i <= 2015; $i++){?>
		<option value="<?php echo $i;?>"><?php echo $i; ?></option>
		<?php }?>
	</select>
	<button type="button" class="btn btn-default" aria-label="Left Align" onclick="refreshTimePolicy(false);">
	 	<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>Tarihe Git
	</button>
	<div id="policy_req_table">
		<div id="user_table" class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<td><b>Durum</b></td>
						<td><b>Talep No</b></td>
						<td><b>Teklif No</b></td>
						<td><b>Teklif Veren</b></td>
						<td><b>Poliçe</b></td>
						<td><b>Teklif tarihi</b></td>
						<td><b>Plaka</b></td>
						<td><b>Şirket</b></td>
						<td><b>Aç</b></td>
					</tr>
					<tr>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($allPolicyRequests as $policyRequest){ ?>
					<?php array_push($request_id_list, $policyRequest[PolicyRequest::REQUEST_ID]);?>
					<tr <?php if($policyRequest[PolicyRequest::STATUS] == 3) echo 'class="row-offer-cancelled"'; ?>>
						<td id="request_<?php echo $policyRequest[PolicyRequest::REQUEST_ID]; ?>">
							<img id='mail_gif' width='24'>
						</td>
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
</div>
<script type="text/javascript">
	pullNewChatEntries(<?php echo "'".implode(',', $request_id_list)."'"; ?>, 1);
	$('#month').val(<?php echo $month; ?>);
	$('#year').val(<?php echo $year; ?>);
	$('#branch_3').addClass("active");
</script>
</body>