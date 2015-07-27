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
		if($user[User::ROLE] != User::PERSONEL){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	
	$offerService = new OfferService();
	$time = date(DateUtil::DB_DATE_FORMAT, time() - DateUtil::OFFER_REQUEST_TIMEOUT_MILLIS);//before 48 hour
	$allOfferRequest = $offerService->getAllRequests($time, null, 1);//Tüm kullanıcıların poliçe isteği yapılmamış taleplerini getir.
	//offer polling job
	Cookie::put(Cookie::LAST_ENTER_OFFER_REQ, date(DateUtil::DB_DATE_FORMAT_TIME), Cookie::REMEMBER_EXPIRE);//son sayfa yenilemeyi cookie'ye yaz
	Cookie::put(Cookie::LE_OFFER_FLAG, "off", Cookie::REMEMBER_EXPIRE);
	
	if(empty($allOfferRequest)){
		?>
			<div id="user_table_msg" align="center">
				<div class="alert alert-warning" role="alert">Hiç talep bulunmamaktadır.</div>
			</div>
		<?php
	}
	
	$companyService = new CompanyService();
	$companies = $companyService->getAll();
	
	if(Cookie::exists('companies')){
		$cookieCompanies = array_filter(json_decode(Cookie::get('companies')));//remove null elements
	}else{
		$cookieCompanies = array();
	}
?>
<div class="container">
	<div class="row">
    	<div class="col-lg-12">
	    	<div class="button-group">
	        	<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="badge" id="num_of_selected"><?php if(isset($cookieCompanies)){echo count($cookieCompanies);}else{echo 0;}?></span>&nbsp;Şirket seç<span class="caret"></span></button>
				<ul class="dropdown-menu">
					<?php foreach ($companies as $company){?>
					<?php 	if($company[Company::ACTIVE] == Company::IS_ACTIVE){?>
						<li><a href="#" class="small" data-value="<?php echo $company[Company::ID]?>" tabIndex="-1"><input id="comp_<?php echo $company[Company::ID]?>" type="checkbox"/><?php echo $company[Company::NAME];?></a></li>
					<?php 	}?>
					<?php } ?>
				</ul>
				<button type="button" class="btn btn-default btn-sm" onclick="location.reload();">Yenile</button>
			</div>
		</div>
	</div>
	<script src="/positive/js/dropdown.js"></script>
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Talep No</b></td>
					<td><b>Teklif Sayısı</b></td>
					<td><b>Kullanıcı Adı</b></td>
					<td><b>Poliçe</b></td>
					<td><b>İstek Tarihi</b></td>
					<td><b>Plaka</b></td>
					<td><b>Aç</b></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
			<?php $userService = new UserService(); ?>
			<?php foreach ($allOfferRequest as $offerRequest){ ?>
				<?php 
					//Check if this contains requested companies
					if(isset($cookieCompanies)){
						$showFlag = false;
						foreach ($offerRequest[OfferRequest::COMPANIES] as $company){
							if(in_array($company[Company::ID], $cookieCompanies)){
								$showFlag = true;
							}
						}
						if(!$showFlag){
							continue;
						}
						$rowOfferCompleted = true;
						foreach ($offerRequest[OfferRequest::COMPANIES] as $company){
							foreach ($cookieCompanies as $companyId){
								if($company[Company::ID] == $companyId){
									if($company[OfferRequest::OFFER_ID] == 0){
										$rowOfferCompleted = false;
									}
								}
							}
						}
					}
				?>
				<?php $tempUser = $userService->getUser($offerRequest[OfferRequest::USER_ID]);?>
				<tr <?php if($rowOfferCompleted) echo "class='row-offer-completed'";?>>
					<td><b><?php echo $offerRequest[OfferRequest::ID]; ?></b></td>
					<td><?php echo $offerService->getGivenOfferRatio($offerRequest[OfferRequest::ID]); ?></td>
					<td><?php echo $tempUser[User::NAME]; ?></td>
					<td><?php echo $offerRequest[OfferRequest::POLICY_TYPE]; ?></td>
					<td><?php echo DateUtil::format($offerRequest[OfferRequest::CREATION_DATE]); ?></td>
					<td><?php echo $offerRequest[OfferRequest::PLAKA]; ?></td>
					<td>
						<button id="remove_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/personel/offer.php?request_id=<?php echo urldecode($offerRequest[OfferRequest::ID]);?>';">
						  <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
						</button>
					</td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$('#personel_1').addClass("active");
</script>
</body>