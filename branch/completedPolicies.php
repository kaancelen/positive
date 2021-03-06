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
	//policy polling job
	Cookie::put(Cookie::LAST_ENTER_POLICY_PAGE, date(DateUtil::DB_DATE_FORMAT_TIME), Cookie::REMEMBER_EXPIRE);//son sayfa yenilemeyi cookie'ye yaz
	Cookie::put(Cookie::LE_POLICY_PAGE_FLAG, "off", Cookie::REMEMBER_EXPIRE);
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
	$allPolicies = $offerService->getCompletedPolicies($user[User::ID], $month, $year, null);
?>
<script src="/positive/js/comp_policy.js"></script>
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
	<button type="button" class="btn btn-default" aria-label="Left Align" onclick="refreshTime(false);">
	 	<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>Tarihe Git
	</button>
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
							onclick="location.href = '/positive/branch/policyDetail.php?policy_id=<?php echo $policySummary[Policy::POLICY_ID];?>'">
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
	$('#month').val(<?php echo $month; ?>);
	$('#year').val(<?php echo $year; ?>);
	$('#branch_4').addClass("active");
</script>
</body>