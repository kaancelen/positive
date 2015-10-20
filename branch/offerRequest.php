<!-- BRANCH -->
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
	include_once (__DIR__.'/../files/FileUploader.php');
	if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
		Util::redirect("/positive/profile.php");
	}
	
	$agentService = new AgentService();
	$agent = $agentService->get($user[User::ID]);
	
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
	
	if(!empty($_POST)){
		$hiddenType = Util::cleanInput($_POST['hiddenType']);
		if($hiddenType == 'uretim'){
			//take company ids
			$companyIds = array();
			foreach ($_POST as $key => $value){
				if (strpos($key,'company') !== false) {
					$temp = explode("company_", $key);
					array_push($companyIds, (int)$temp[1]);
				}
			}
			
			$plaka = Util::cleanInput($_POST['plaka']);
			$tckn = Util::cleanInput($_POST['tckn']);
			$vergi = Util::cleanInput($_POST['vergiNo']);
			$belge = Util::cleanInput($_POST['belgeNo']);
			$asbis = Util::cleanInput($_POST['asbis']);
			$description = Util::cleanInput($_POST['description']);
			$user_id = $user[User::ID];
			$policy_type = "Tanımsız";
			$policy_text = "Tanımsız";
			
			if(isset($_POST['radio_trafik'])){
				$policy_type = PolicyType::TRAFIK;
				$policy_text = "Trafik poliçe";
			}else if(isset($_POST['radio_kasko'])){
				$policy_type = PolicyType::KASKO;
				$policy_text = "Kasko poliçe";
			}else if(isset($_POST['radio_kasko_trafik'])){
				$policy_type = PolicyType::KASKO_TRAFIK;
				$policy_text = "Kasko ve Trafik poliçe";
			}else if(isset($_POST['radio_other'])){
				$policy_type = PolicyType::DIGER;
				$policy_text = "Poliçe";
			}
			
			$offerService = new OfferService();
			$offerRequestId = null;
			
			if($policy_type == PolicyType::KASKO_TRAFIK){
				$offerRequestId = $offerService->addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, PolicyType::KASKO, $user_id, $companyIds);
				$offerRequestId = $offerService->addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, PolicyType::TRAFIK, $user_id, $companyIds);
			}else if($policy_type == PolicyType::DIGER){
				$offerRequestId = $offerService->addOfferRequest("", "", "", "", "", $description, $policy_type, $user_id, $companyIds);
			}else{
				$offerRequestId = $offerService->addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $policy_type, $user_id, $companyIds);
			}
			
			if(!is_null($offerRequestId)){
				Session::flash(Session::FLASH, $policy_text." talebi sisteme eklendi.");
				Util::redirect("/positive/branch/offer.php?request_id=".$offerRequestId);
			}else{ ?>
				<div id="user_form_msg" align="center">
					<div class="alert alert-danger" role="alert">Teklif oluşturulamadı, bir hata ile karşılaşıldı!</div>
				</div>
				<?php
			}
		}else if($hiddenType == 'iptal'){
			$policy = Util::cleanInput($_POST['policyNo']);
			$companyId = Util::cleanInput($_POST['company']);
			$typeOfContract = Util::cleanInput($_POST['type']);
			$desc = Util::cleanInput($_POST['desc']);
			$ext = strtolower(pathinfo($_FILES['sozlesme']['name'], PATHINFO_EXTENSION));//get extension
			
			$fileUploader = new FileUploader();
			$filepath = $fileUploader->uploadCancelFile('c_'.$policy.'.'.$ext, $_FILES['sozlesme']);
			if(is_null($filepath)){
				?>
				<div align="center">
					<div class="alert alert-danger" role="alert">Dosya yüklenemedi, bir hata ile karşılaşıldı!</div>
				</div>
				<?php
			}else{
				$cancelService = new CancelService();
				$cancelService->insert($user[User::ID], $filepath, $policy, $companyId, $typeOfContract, $desc);
				Util::redirect('/positive/branch/policyCancels.php');
			}
		}
	}
?>
<script src="/positive/js/offer.js"></script>
<div class="request-main">
	<div class="request-type-buttons">
		<div class="row">
			<div class="col-md-6" style="text-align: center">
				<a href="#" class="thumbnail" onclick="request_input(0);"><h2>ÜRETİM</h2></a>
			</div>
			<div class="col-md-6" style="text-align: center">
				<a href="#" class="thumbnail" onclick="request_input(1);"><h2>İPTAL</h2></a>
			</div>
		</div>
	</div>
	<div class="uretim-main" style="display: none">
		<form class="form-signin" id="offer-request-form" action="" method="post" autocomplete="off">
			<input type="hidden" value="uretim" id="hiddenType" name="hiddenType">
			<div class="container offer-screen">
				<div class="companies-column well">
					<label class="login-error" id="offer-request-company-error"></label>
<!-- 				    <div class="input-group"> -->
<!-- 				      <span class="input-group-addon"> -->
				       <!-- <input type="checkbox" id="company_all" onchange='onCompanyAllChange(<?php echo json_encode($companies);?>)'> -->
<!-- 				      </span> -->
<!-- 				      <label class="form-control" readonly>Hepsini Seç</label> -->
<!-- 				    </div> -->
				    <?php foreach ($companies as $company){?>
				    <div class="input-group">
				    	<?php if($company[Company::ACTIVE] == Company::IS_ACTIVE){ ?>
							      <span class="input-group-addon">
							        <input type="checkbox" id="company_<?php echo $company[Company::ID];?>" name="company_<?php echo $company[Company::ID];?>">
							      </span>
							      <label class="form-control"><?php echo $company[Company::NAME];?></label>
				      	<?php }else{ ?>
								  <span class="input-group-addon"></span>
								  <label class="form-control" readonly><?php echo $company[Company::NAME];?></label>
				      	<?php }?>
				    </div>
				    <?php }?>
				</div>
				<div class="offer-column well">
					<h2 class="form-signin-heading">Talep Bilgileri</h2>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">
							<input type="radio" id="radio_trafik" name="radio_trafik" checked="checked" onchange="on_radio_trafik_change()">
							Trafik Poliçesi
						</span>
						<span class="input-group-addon" id="basic-addon1">
							<input type="radio" id="radio_kasko" name="radio_kasko" onchange="on_radio_kasko_change()">
							Kasko Poliçesi
						</span>
						<span class="input-group-addon" id="basic-addon1">
							<input type="radio" id="radio_kasko_trafik" name="radio_kasko_trafik" onchange="on_radio_kasko_trafik_change()">
							Kasko ve Trafik Poliçesi
						</span>
						<span class="input-group-addon" id="basic-addon1">
							<input type="radio" id="radio_other" name="radio_other" onchange="on_radio_other_change()">
							Diğer
						</span>
					</div>
					<br>
					<label class="login-error" id="offer-request-error"></label>
					<div id="not_desc_fields">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Plaka No</span>
							<input type="text" class="form-control" aria-describedby="basic-addon1" id="plaka" name="plaka" placeholder="99 XXX 99999">
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">
								<input type="radio" id="radio_tckn" checked="checked" onchange="on_radio_tckn_change()">
								TC Kimlik No
							</span>
							<input type="text" class="form-control" aria-describedby="basic-addon1" id="tckn" name="tckn">
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">
								<input type="radio" id="radio_vergi" onchange="on_radio_vergi_change()">
								Vergi No
							</span>
							<input type="text" readonly class="form-control" aria-describedby="basic-addon1" id="vergiNo" name="vergiNo">
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">
								<input type="radio" id="radio_belge" checked="checked" onchange="on_radio_belge_change()">
								Belge No
							</span>
							<input type="text" class="form-control" aria-describedby="basic-addon1" id="belgeNo" name="belgeNo">
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">
								<input type="radio" id="radio_asbis" onchange="on_radio_asbis_change()">
								ASBİS
							</span>
							<input type="text" readonly class="form-control" aria-describedby="basic-addon1" id="asbis" name="asbis">
						</div>
						<br>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">
							Ek Bilgi
						</span>
						<textarea rows="4" cols="30" class="form-control" aria-describedby="basic-addon1" id="description" name="description" placeholder="Müşteri adı soyadı, sigorta ile alakalı diğer ek bilgiler"></textarea>
					</div>
					<h5><small><b>En fazla 2048 karakter</b></small></h5>
					<br>
					<button class="btn btn-lg btn-primary btn-block" type="button" onclick='validateOfferRequest(<?php echo json_encode($companies);?>)' id="offer-request-button">Teklif iste</button>
				</div>
			</div>
		</form>
	</div>
	<div class="iptal-main" style="display: none">
		<form class="form-signin" id="cancel-request-form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
			<input type="hidden" value="iptal" id="hiddenType" name="hiddenType">
			<div class="cancel_request_fields container well">
				<h2>İptal Bilgileri</h2>
				<label class="login-error" id="cancel-request-error"></label>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Noter satış sözleşmesi eki</span>
					<input type="file" class="form-control" aria-describedby="basic-addon1" id="sozlesme" name="sozlesme">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Poliçe No</span>
					<input type="text" class="form-control" aria-describedby="basic-addon1" id="policyNo" name="policyNo">
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Şirket</span>
					<select id="company" name="company" class="form-control">
						<option value="0">Şirket Seçiniz</option>
						<?php foreach ($companies as $company){?>
							<option value="<?php echo $company[Company::ID];?>"><?php echo $company[Company::NAME];?></option>
						<?php }?>
					</select>
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Poliçe türü</span>
					<select id="type" name="type" class="form-control">
						<option value="0">Poliçe türü Seçiniz</option>
						<option value="<?php echo PolicyType::KASKO;?>"><?php echo PolicyType::KASKO;?></option>
						<option value="<?php echo PolicyType::TRAFIK;?>"><?php echo PolicyType::TRAFIK;?></option>
						<option value="<?php echo PolicyType::DIGER;?>"><?php echo PolicyType::DIGER;?></option>
					</select>
				</div>
				<br>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Ek bilgi</span>
					<textarea rows="4" cols="30" class="form-control" aria-describedby="basic-addon1" id="desc" name="desc" placeholder="Sigorta iptali ile alakalı diğer ek bilgiler."></textarea>
				</div>
				<br>
				<button class="btn btn-lg btn-primary btn-block" type="button" id="cancel-request-button" onclick="validateCancelRequest();">İptal iste</button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$('#branch_1').addClass("active");
</script>
</body>