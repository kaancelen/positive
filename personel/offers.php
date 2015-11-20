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
	
	//offer polling job
	Cookie::put(Cookie::LAST_ENTER_OFFER_REQ, date(DateUtil::DB_DATE_FORMAT_TIME), Cookie::REMEMBER_EXPIRE);//son sayfa yenilemeyi cookie'ye yaz
	Cookie::put(Cookie::LE_OFFER_FLAG, "off", Cookie::REMEMBER_EXPIRE);
	
	if(Cookie::exists('companies')){
		$cookieCompanies = array_filter(json_decode(Cookie::get('companies')));//remove null elements
	}else{
		$cookieCompanies = array();
	}
	
	$userService = new UserService();
	$agents = $userService->allTypeOfUsers(User::BRANCH);
	
	$offerService = new OfferService();
	if(!empty($cookieCompanies)){
		$allOfferRequest = $offerService->getPersonelRequests($cookieCompanies);//Tüm kullanıcıların poliçe isteği yapılmamış taleplerini getir.
	}else{
		$allOfferRequest = array();
	}
	
	if(empty($allOfferRequest)){
		?>
			<div id="user_table_msg" align="center">
				<div class="alert alert-warning" role="alert">Hiç talep bulunmamaktadır.</div>
			</div>
		<?php
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
?>
<script src="/positive/js/pullNewChat.js"></script>
<div class="container">
	<div class="row">
    	<div class="col-lg-4">
	    	<div class="button-group">
	        	<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="badge" id="num_of_selected"><?php if(isset($cookieCompanies)){echo count($cookieCompanies);}else{echo 0;}?></span>&nbsp;Şirket seç<span class="caret"></span></button>
				<ul class="dropdown-menu dropdown">
					<?php foreach ($companies as $company){?>
					<?php 	if($company[Company::ACTIVE] == Company::IS_ACTIVE){?>
						<li><a href="#" class="small" data-value="<?php echo $company[Company::ID]?>" tabIndex="-1"><input id="comp_<?php echo $company[Company::ID]?>" type="checkbox"/><?php echo $company[Company::NAME];?></a></li>
					<?php 	}?>
					<?php } ?>
				</ul>
				<button type="button" class="btn btn-default btn-sm" onclick="location.reload();">Yenile</button>
			</div>
		</div>
		<div class="col-lg-2">
		</div>
		<div class="col-lg-6">
		</div>
	</div>
	<script src="/positive/js/dropdown.js"></script>
	<div class="table-responsive">
		<table id="request_table" class="table">
			<thead>
				<tr>
					<td><b>Durum</b></td>
					<td><b>Talep No</b></td>
					<td><b>Teklif Sayısı</b></td>
					<td><b>Acente</b></td>
					<td><b>Poliçe</b></td>
					<td><b>İstek Tarihi</b></td>
					<td><b>Plaka</b></td>
					<td><b>Aç</b></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
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
					<td>
						<select id="selected_policy_type" name="selected_policy_type" class="form-control" onchange="onDropdownChange();">
							<option value="NULL">Tüm Poliçeler</option>
							<option value="Kasko">Kasko</option>
							<option value="Trafik">Trafik</option>
							<option value="Diger">Diger</option>
						</select>
					</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</thead>
			<tbody>
			<?php $userService = new UserService(); ?>
			<?php foreach ($allOfferRequest as $offerRequest){ ?>
				<?php 
					$class_data = "row-offer-nothing";
					if($offerRequest[OfferList::STATUS] == 2){
						$class_data = "row-offer-cancelled";
					}else if($offerRequest[OfferList::WAITING_OFFER_NUM] == 0){
						$class_data = "row-offer-completed";
					}
				?>
				<tr class="<?php echo $class_data; ?>">
					<td id="request_<?php echo $offerRequest[OfferList::ID]; ?>">
						<img id='mail_gif' width='24'>
						<img id='look_gif' width='24'>
					</td>
					<td><b id="req_id"><?php echo $offerRequest[OfferList::ID]; ?></b></td>
					<td><?php echo $offerRequest[OfferList::OFFER_RATE];?></td>
					<td><?php echo $offerRequest[OfferList::BRANCH_NAME]; ?></td>
					<td><?php echo $offerRequest[OfferList::POLICY_TYPE]; ?></td>
					<td id="date"><?php echo DateUtil::format($offerRequest[OfferList::CREATION_DATE]); ?></td>
					<td><?php echo $offerRequest[OfferList::PLAKA]; ?></td>
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
		<?php if(false){//if(count($allOfferRequest) > 0){?>
			<script src="/positive/js/lazy_loading.js"></script>
			<div class="alert alert-info" style="width: 100%;text-align: center" role="alert">
				<a id="get_others_link" onclick="getOtherRequests();">Devamını getir</a>
				<img id="loading_gif" src="/positive/images/loader.gif" style="visibility: hidden;">
				<label id="request_finished" style="visibility: hidden;">Tüm talepler görüntüleniyor.</label>
			</div>
		<?php }?>
	</div>
</div>
<script type="text/javascript">
	pullNewChatEntries();
	$('#personel_1').addClass("active");
</script>
</body>