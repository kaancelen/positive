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
	$allPolicies = $offerService->getCompletedPolicies($user[User::ID]);
?>
<div class="container">
	<div id="policy_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Poliçe No</b></td>
					<td><b>Plaka</b></td>
					<td><b>Şirket</b></td>
					<td><b>Onay tarihi</b></td>
					<td><b>Talep eden</b></td>
					<td><b>Teklif veren</b></td>
					<td><b>Poliçeyi yapan</b></td>
					<td><b>Aç</b></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($allPolicies as $policySummary){ ?>
				<tr>
					<td><b><?php echo $policySummary[Policy::POLICY_ID]; ?></b></td>
					<td><?php echo $policySummary[Policy::PLAKA]; ?></td>
					<td><?php echo $policySummary[Policy::COMPANY_NAME]; ?></td>
					<td><?php echo DateUtil::format($policySummary[Policy::POLICY_COMPLETE_DATE]); ?></td>
					<td><?php echo $policySummary[Policy::BRANCH_NAME]; ?></td>
					<td><?php echo $policySummary[Policy::PERSONEL_NAME]; ?></td>
					<td><?php echo $policySummary[Policy::POLICY_COMPLETE_PERSONEL]; ?></td>
					<td>
						<button id="open_policy_req_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/branch/policyDetails.php?policy_id=<?php echo $policySummary[Policy::POLICY_ID];?>'">
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
	$('#branch_4').addClass("active");
</script>
</body>