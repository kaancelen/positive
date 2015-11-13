<!-- ADMIN -->
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
		if($user[User::ROLE] != User::ADMIN){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	
	$companyService = new CompanyService();
	$companyList = $companyService->getAll();
	
?>
<div class="container">
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Durum</b></td>
					<td><b>Şirket</b></td>
					<td><b>İç/Dış</b></td>
					<td><b>Üretim Kanalı</b></td>
					<td>
						<button type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/admin/companyDetail.php'">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;Ekle
						</button>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($companyList as $company){?>
					<?php $stateImg = ($company[Company::ACTIVE] == Company::IS_ACTIVE ? "/positive/images/green.png" : "/positive/images/red.png");?>
					<tr>
						<td><img src="<?php echo $stateImg;?>"></td>
						<td><?php echo $company[Company::NAME];?></td>
						<td><?php echo $company[Company::IC_DIS];?></td>
						<td><?php echo $company[Company::URETIM_KANALI];?></td>
						<td>
							<button id="edit_company" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
								onclick="location.href = '/positive/admin/companyDetail.php?company_id=<?php echo urldecode($company[Company::ID])?>';">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
							</button>
<!-- 							<button id="remove_company" type="button" class="btn btn-default btn-sm" aria-label="Left Align"> -->
<!-- 								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> -->
<!-- 							</button> -->
						</td>
					</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
</div>
<script src="/positive/js/companyDetail.js"></script>
<script type="text/javascript">
	$('#admin_4').addClass("active");
</script>
</body>