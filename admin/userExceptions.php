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
<script src="/positive/js/report.js"></script>
<div class="container">
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>ID</b></td>
					<td><b>Durum</b></td>
					<td><b>Başlık</b></td>
					<td><b>Rapor eden</b></td>
					<td><b>Kullanıcı id</b></td>
					<td><b>Rapor tarihi</b></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($reports as $report){ ?>
				<?php 
					switch ($report[UserReport::STATUS]){
						case 1: $image = "/positive/images/yellow.png";break;
						case 2: $image = "/positive/images/green.png";break;
						case 0: 
						default: $image = "/positive/images/red.png";break; 
					}
				?>
			
				<tr id="report_<?php echo $report[UserReport::ID]; ?>">
					<td><b><?php echo $report[UserReport::ID]; ?></b></td>
					<td><img src="<?php echo $image; ?>"> </td>
					<td><?php echo $report[UserReport::SUBJECT]; ?></td>
					<td><?php echo $report[UserReport::USER_NAME]; ?></td>
					<td><?php echo $report[UserReport::USER_ID]; ?></td>
					<td><?php echo DateUtil::format($report[UserReport::CREATION_DATE]); ?></td>
					<td>
						<button id="make_policies_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/admin/userException.php?report_id=<?php echo urlencode($report[UserReport::ID]);?>'">
						  <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
						</button>
					</td>
					<td>
						<button id="make_policies_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="removeReport(<?php echo $report[UserReport::ID]; ?>)">
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