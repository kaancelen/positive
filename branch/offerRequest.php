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
	if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
		Util::redirect("/positive/profile.php");
	}
	
	$agentService = new AgentService();
	$agent = $agentService->get($user[User::ID]);
	
	$companyService = new CompanyService();
	$companies = $companyService->getAll();
	
	if(!empty($_POST)){
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
		
		$offerService = new OfferService();
		$offerRequestId = $offerService->addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $user_id, $companyIds);
		if(!is_null($offerRequestId)){
			Util::redirect("/positive/branch/offer.php?request_id=".$offerRequestId);
		}else{ ?>
			<div id="user_form_msg" align="center">
				<div class="alert alert-danger" role="alert">Teklif oluşturulamadı, bir hata ile karşılaşıldı!</div>
			</div>
			<?php
		}
	}
	
?>
<script src="/positive/js/offer.js"></script>
<form class="form-signin" id="offer-request-form" action="" method="post" autocomplete="off">
	<div class="container offer-screen">
		<div class="companies-column well">
			<label class="login-error" id="offer-request-company-error"></label>
		    <div class="input-group">
		      <span class="input-group-addon">
		        <input type="checkbox" id="company_all" onchange='onCompanyAllChange(<?php echo json_encode($companies);?>)'>
		      </span>
		      <label class="form-control" readonly>Hepsini Seç</label>
		    </div>
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
			<label class="login-error" id="offer-request-error"></label>
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
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">
					Ek Bilgi
				</span>
				<textarea rows="4" cols="30" class="form-control" aria-describedby="basic-addon1" id="description" name="description" placeholder="Müşteri adı soyadı, araba markası, rengi vs."></textarea>
			</div>
			<br>
			<button class="btn btn-lg btn-primary btn-block" type="button" onclick='validateOfferRequest(<?php echo json_encode($companies);?>)' id="offer-request-button">Teklif iste</button>
		</div>
	</div>
</form>
</body>