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
	$time = date(DateUtil::DB_DATE_FORMAT, time() - DateUtil::POLICY_TIMEOUT_MILLIS);//before 30 day
	$allPolicies = $offerService->getCompletedPolicies(null, $time);
?>
<div class="container">
	<div id="policy_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Poliçe No</b></td>
					<td><b>Poliçe</b></td>
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
					<td><b><?php echo $policySummary[Policy::POLICY_NUMBER]; ?></b></td>
					<td><?php echo $policySummary[Policy::POLICY_TYPE]; ?></td>
					<td><?php echo $policySummary[Policy::PLAKA]; ?></td>
					<td><?php echo $policySummary[Policy::COMPANY_NAME]; ?></td>
					<td><?php echo DateUtil::format($policySummary[Policy::POLICY_COMPLETE_DATE]); ?></td>
					<td><?php echo $policySummary[Policy::BRANCH_NAME]; ?></td>
					<td><?php echo $policySummary[Policy::PERSONEL_NAME]; ?></td>
					<td><?php echo $policySummary[Policy::POLICY_COMPLETE_PERSONEL]; ?></td>
					<td>
						<button id="open_policy_req_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/personel/policyDetail.php?policy_id=<?php echo $policySummary[Policy::POLICY_ID];?>'">
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
	$('#personel_3').addClass("active");
</script>
</body>