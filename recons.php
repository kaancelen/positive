<!-- BRANCH -->
<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/Util/init.php');
	include_once (__DIR__.'/classes/Recon.php');
	if($loggedIn){
		$user = Session::get(Session::USER);
	}
	include_once (__DIR__.'/navigationBar.php');
	
	if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
		Util::redirect("/positive/profile.php");
	}
	
	if(isset($_GET['month']) && isset($_GET['year'])){
		$month = Util::cleanInput(urldecode($_GET['month']));
		$year = Util::cleanInput(urldecode($_GET['year']));
	}else{
		$month = date('n');
		$year = date('Y');
		
		if($month == 1){
			$month = 12;
			$year = $year - 1;
		}else{
			$month = $month - 1;
		}
	}
	
	$reconService = new ReconService();
	$reconDifference = $reconService->reconDifferent($month, $year, $user[User::ID], $user[User::ROLE]);
	
	$allRecons = $reconService->getRecons($month, $year, $user[User::ID], $user[User::ROLE]);
?>
	<script src="/positive/js/recon.js"></script>
	<div class="container">
		<?php $monthMap = Util::getMonthMap();?>
		<select id="recon_month" name="recon_month" class="form-control month-option">
			<?php foreach ($monthMap as $key => $value) {?>
			<option value="<?php echo $key;?>"><?php echo $value; ?></option>
			<?php }?>
		</select>
		<select id="recon_year" name="recon_year" class="form-control month-option">
			<?php for($i=2015; $i <= 2015; $i++){?>
			<option value="<?php echo $i;?>"><?php echo $i; ?></option>
			<?php }?>
		</select>
		<button type="button" class="btn btn-default" aria-label="Left Align" onclick="refreshReconTime();">
		 	<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>Tarihe Git
		</button>
		<a target="_blank" href="/positive/downloadReconXml.php?month=<?php echo $month;?>&year=<?php echo $year;?>">XML dosyası olarak indir.</a>
		<a target="_blank" href="http://www.luxonsoftware.com/converter/xmltoexcel">Ama bana excel lazım!</a>
	</div>
	<div class="container">
		<?php if($reconDifference > 0){ ?>
		<div class="alert alert-info" role="alert">
			<b><?php echo $reconDifference; ?></b> adet mutabakat eksik.
			Eksik mutabakatları oluşturmak için 
			<a href="#" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" 
			data-keyboard="false" onclick="refreshReconTable();"><b>tıklayınız.</b></a>
		</div>
		<?php }?>
		<?php if($reconDifference < 0){ ?>
		<div class="alert alert-danger" role="alert">Mutabakat kontrolünde bir hata ile karşılaşıldı.</div>
		<?php }?>
		<div id="recon_table" class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<td><b>Takip No</b></td>
						<td><b>Tanzim Tarihi</b></td>
						<td><b>Şirket</b></td>
						<td><b>Poliçe No</b></td>
						<td><b>Poliçe Türü</b></td>
						<td><b>Prodüktör</b></td>
						<td><b>Aç</b></td>
					</tr>
					<tr>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($allRecons as $recon){ ?>
					<?php 
						$class = $reconService->isReconCompleted($user[User::ROLE], $recon);
					?>
					<tr class="<?php echo $class;?>">
						<td><?php echo $recon[Recon::TAKIP_NO]; ?></td>
						<td><?php echo DateUtil::format($recon[Recon::TANZIM_TARIHI]);?></td>
						<td><?php echo $recon[Recon::SIRKET]; ?></td>
						<td><?php echo $recon[Recon::POLICE_NO]; ?></td>
						<td><?php echo $recon[Recon::POLICE_TURU]; ?></td>
						<td><?php echo $recon[Recon::PRODUKTOR]; ?></td>
						<td>
							<button id="make_policies_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
								onclick="location.href = '/positive/reconDetail.php?recon_id=<?php echo urldecode($recon[Recon::TAKIP_NO]);?>';">
							  <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div id="loading_modal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
		style="opacity: 0.6">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content">
	    	<img alt="Yükleniyor." src="/positive/images/294.gif">
	    </div>
	  </div>
	</div>
	<script type="text/javascript">
		$('#recon_month').val(<?php echo $month; ?>);
		$('#recon_year').val(<?php echo $year; ?>);
		$('#recon_1').addClass("active");
	</script>
</body>