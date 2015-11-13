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
	$company = null;
	
	$is_add = true;
	if(isset($_GET['company_id'])){
		$is_add = false;
		$company_id = Util::cleanInput(urldecode($_GET['company_id']));
		$company = $companyService->getCompany($company_id);
	}
	
	//Form submit
	if(!empty($_POST)){
		$durum = Util::cleanInput($_POST['durum']);
		$name = Util::cleanInput($_POST['name']);
		$ic_dis = Util::cleanInput($_POST['ic_dis']);
		$uretim_kanali = Util::cleanInput($_POST['uretim_kanali']);

		$alert_type = "danger";
		$message = "güncelleme";
		$message_2 = "başarısız oldu";
		
		if($is_add){
			$new_company_id = $companyService->addCompany($durum, $name, $ic_dis, $uretim_kanali);
			if(!is_null($new_company_id)){
				$alert_type = "info";
				$message_2 = "başarı ile tamamlandı";
				$company = $companyService->getCompany($new_company_id);
			}
			$message = "ekleme";
		}else{
			$result = $companyService->editCompany($company_id, $durum, $name, $ic_dis, $uretim_kanali);
			if($result){
				$alert_type = "info";
				$message_2 = "başarı ile tamamlandı";
				$company = $companyService->getCompany($company_id);
			}
		}
		
		echo '<div align="center">';
		echo '<div class="alert alert-'.$alert_type.'" role="alert">Şirket '.$message.' işleminiz '.$message_2.'</div>';
		echo '</div>';
	}
?>
<div class="container user_form">
	<div class="well well-lg">
		<h2 class="form-signin-heading">Şirket Bilgileri</h2>
		<form class="form-signin" id="company_form" action="" method="post" autocomplete="off">
			<label class="login-error" id="company-error"></label>
	        <div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Durum</span>
				<select id="durum" name="durum" class="form-control">
					<option value="">Seçiniz</option>
					<option value="1" style="color: green;">Aktif</option>
					<option value="0" style="color: red;">Pasif</option>
				</select>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Şirket</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="name" name="name">
			</div>
	        <br>
	        <div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Durum</span>
				<select id="ic_dis" name="ic_dis" class="form-control">
					<option value="">Seçiniz</option>
					<option value="İÇ">İÇ</option>
					<option value="DIŞ">DIŞ</option>
				</select>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Üretim Kanalı</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="uretim_kanali" name="uretim_kanali">
			</div>
			<br>
			<button class="btn btn-lg btn-primary btn-block" type="button" id="submit_button"
	        	onclick="validateCompanyForm()">
	        	<?php echo ($is_add ? "Ekle" : "Güncelle"); ?>
	        </button>
			
		</form>
	</div>
</div>
<script src="/positive/js/companyDetail.js"></script>
<script type="text/javascript">
	<?php if(!is_null($company)){?>
		$('#durum').val('<?php echo $company[Company::ACTIVE];?>');
		$('#name').val('<?php echo $company[Company::NAME];?>');
		$('#ic_dis').val('<?php echo $company[Company::IC_DIS];?>');
		$('#uretim_kanali').val('<?php echo $company[Company::URETIM_KANALI];?>');
	<?php }?>
</script>
<script type="text/javascript">
	$('#admin_4').addClass("active");
</script>
</body>