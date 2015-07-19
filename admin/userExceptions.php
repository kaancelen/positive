<!-- ADMIN -->
<head>
<?php 
	include_once(__DIR__.'/../head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/../Util/init.php');
	include_once (__DIR__.'/../service/UserReportService.php');
	if($loggedIn){
		$user = Session::get(Session::USER);
		if($user[User::ROLE] != User::ADMIN){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	
	$userReportService = new UserReportService();
	$reports = $userReportService->getAll();
	
?>
<div class="container">
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>ID</b></td>
					<td><b>Durum</b></td>
					<td><b>Başlık</b></td>
					<td><b>Rapor eden</b></td>
					<td><b>Rapor tarihi</b></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($reports as $report){ ?>
				<tr>
					<td><b><?php echo $report[UserReport::ID]; ?></b></td>
					<td><?php echo $report[UserReport::STATUS]; ?></td>
					<td><?php echo $report[UserReport::SUBJECT]; ?></td>
					<td><?php echo $report[UserReport::USER_NAME]; ?></td>
					<td><?php echo DateUtil::format($report[UserReport::CREATION_DATE]); ?></td>
					<td>
						<button id="make_policies_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="">
						  <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
						</button>
					</td>
					<td>
						<button id="make_policies_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="">
						  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
						</button>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$('#admin_2').addClass("active");
</script>
<body>