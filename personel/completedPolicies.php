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
	
	if(isset($_GET['month']) && isset($_GET['year'])){
		$month = Util::cleanInput(urldecode($_GET['month']));
		$year = Util::cleanInput(urldecode($_GET['year']));
	}else{
		$month = date('n');
		$year = date('Y');
	}
	
	$companyService = new CompanyService();
	$companies = $companyService->getAll();
	
	if($user[User::ALLOWED_COMP] != 0){
		$allowed_comp = explode(",", $user[User::ALLOWED_COMP]);
		$temp_companies = array();
		foreach ($companies as $company){
			if(in_array($company[Company::ID], $allowed_comp)){
				array_push($temp_companies, $company);
			}
		}
		$companies = $temp_companies;
	}
	
	$offerService = new OfferService();
	$allowed_comp = null;
	if($user[User::ALLOWED_COMP] != 0){
		$allowed_comp = $user[User::ALLOWED_COMP];
	}
	$allPolicies = $offerService->getCompletedPolicies(null, $month, $year, $allowed_comp);
	
	$userService = new UserService();
	$agents = $userService->allTypeOfUsers(User::BRANCH);
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
	<button type="button" class="btn btn-default" aria-label="Left Align" onclick="refreshTime(true);">
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
					<td></td>
					<td>
						<select id="selected_policy_type" name="selected_policy_type" class="form-control" onchange="onDropdownChange();">
							<option value="NULL">Tüm Poliçeler</option>
							<option value="Kasko">Kasko</option>
							<option value="Trafik">Trafik</option>
							<option value="Diger">Diger</option>
						</select>
					</td>
					<td></td>
					<td>
						<select id="selected_company" name="selected_company" class="form-control" onchange="onDropdownChange();">
							<option value="NULL">Tüm Şirketler</option>
							<?php foreach ($companies as $company){?>
								<option value="<?php echo $company[Company::NAME];?>"><?php echo $company[Company::NAME];?></option>
							<?php }?>
						</select>
					</td>
					<td></td>
					<td>
						<select id="selected_agent" name="selected_agent" class="form-control" onchange="onDropdownChange();">
							<option value="NULL">Tüm Acenteler</option>
							<?php foreach ($agents as $agent){?>
								<?php if($agent[User::FIRST_LOGIN] != User::FIRST_LOGIN_FLAG){?>
									<option value="<?php echo $agent[User::NAME];?>"><?php echo $agent[User::NAME];?></option>
								<?php }?>
							<?php }?>
						</select>
					</td>
					<td></td>
					<td></td>
					<td></td>
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
	$('#month').val(<?php echo $month; ?>);
	$('#year').val(<?php echo $year; ?>);
	$('#personel_3').addClass("active");
</script>
</body>