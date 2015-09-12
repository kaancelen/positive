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
	
	if(isset($_GET['request_id'])){
		$offerRequestId = urlencode($_GET['request_id']);
	}
	if(empty($offerRequestId)){
		Util::redirect('/positive/error/404.php');
	}
	
	$offerService = new OfferService();
	$offerRequest = $offerService->getOfferRequest($offerRequestId);
	if(empty($offerRequest)){
		Util::redirect('/positive/error/404.php');
	}
	
	$offerResponses = $offerService->getOffers($offerRequestId);
	$userService = new UserService();
	
	if(Session::exists(Session::FLASH)){
		?>
		<div id="user_form_msg" align="center">
			<div class="alert alert-success" role="alert"><?php echo Session::get(Session::FLASH); ?></div>
		</div>
		<?php
		Session::delete(Session::FLASH);//Remove message
	}
	$makePolicyPermission = true;
	$alertText = "";
	if($offerRequest[OfferRequest::IS_OFFER_ACCEPTED]){
		$makePolicyPermission = false;
		$alertText = "Bu talep için bir teklif kabul edilmiş ve poliçe isteklerine eklenmiş.";
	
	}
	if($offerRequest[OfferRequest::STATUS] == 2){
		$makePolicyPermission = false;
		$alertText = "Bu talep kapatılmıştır, poliçeleştirilemez.";
	}
	if(!$makePolicyPermission){
	?>
		<div id="user_form_msg" align="center">
			<div class="alert alert-warning" role="alert"><?php echo $alertText; ?></div>
		</div>
		<?php
	}
	
	$genericService = new GenericService();
	$genericService->updateUserEnter($user[User::ID], $offerRequestId, 0);
?>
<script src="/positive/js/branch.js"></script>
<div class="well offer-request-label">
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Talep No</td>
				<td>Poliçe</td>
				<td>İstek tarihi</td>
				<?php if($offerRequest[OfferRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
					<td>Plaka</td>
					<td>TC Kimlik No</td>
					<td>Vergi No</td>
					<td>Belge No</td>
					<td>ASBİS No</td>
				<?php } ?>
				<td>Ek Bilgi</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $offerRequest[OfferRequest::ID];?></td>
				<td><?php echo $offerRequest[OfferRequest::POLICY_TYPE];?></td>
				<td><?php echo DateUtil::format($offerRequest[OfferRequest::CREATION_DATE]);?></td>
				<?php if($offerRequest[OfferRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
					<td><?php echo $offerRequest[OfferRequest::PLAKA];?></td>
					<td><?php echo $offerRequest[OfferRequest::TCKN];?></td>
					<td><?php echo $offerRequest[OfferRequest::VERGI];?></td>
					<td><?php echo $offerRequest[OfferRequest::BELGE];?></td>
					<td><?php echo $offerRequest[OfferRequest::ASBIS];?></td>
				<?php } ?>
				<td><?php echo $offerRequest[OfferRequest::DESCRIPTION];?></td>
			</tr>
		</tbody>
	</table>
</div>
<br>
<div class="container offer-request-screen">
	<div class="offers-column">
		<div id="user_table" class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<td><b>Teklif No</b></td>
						<td><b>Teknikçi</b></td>
						<td><b>Sigorta şirketi</b></td>
						<td><b>Prim</b></td>
						<td><b>Komisyon</b></td>
						<td><b>Prod Komisyonu</b></td>
						<td><b>Poliçeleştir</b></td>
					</tr>
					<tr>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($offerRequest[OfferRequest::COMPANIES] as $company){ ?>
					<tr id="offer_row_<?php echo $company[Company::ID];?>">
						<td id="offer_id_<?php echo $company[Company::ID]; ?>"></td>
						<td id="personel_id_<?php echo $company[Company::ID]; ?>"></td>
						<td><?php echo $company[Company::NAME]; ?></td>
						<td style="width:15%">
							<div class="input-group input-group-sm">
							  <span class="input-group-addon">₺</span>
							  <input type="text" readonly="readonly" class="form-control input-tl" id="prim_<?php echo $company[Company::ID]; ?>">
							</div>
						</td>
						<td style="width:15%">
							<div class="input-group input-group-sm">
							  <span class="input-group-addon">₺</span>
							  <input type="text" readonly="readonly" class="form-control input-tl" id="komisyon_<?php echo $company[Company::ID]; ?>">
							</div>
						</td>
						<td style="width:15%">
							<div class="input-group input-group-sm">
							  <span class="input-group-addon">₺</span>
							  <input type="text" readonly="readonly" class="form-control input-tl" id="prod_komisyon_<?php echo $company[Company::ID]; ?>">
							</div>
						</td>
						<td>
							<?php if($makePolicyPermission){ ?>
							<button id="make_policy_<?php echo $company[Company::ID]; ?>" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
								onclick="navigateToPolicyRequest(<?php echo $company[Company::ID]; ?>);" style="display: none">
							  <span class="glyphicon glyphicon-paste" aria-hidden="true"></span>
							</button>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="well chat-column">
		<?php $chat_request_id = $offerRequest[OfferRequest::ID];?>
		<?php include_once (__DIR__.'/../chat.php'); ?>
	</div>
	<script type="text/javascript">
		var min_value = Number.MAX_VALUE;
		var min_value_comp = 0;
		<?php foreach($offerResponses as $offerResponse){?>
			var temp_value = <?php echo $offerResponse['PRIM'];?>;
			if(temp_value > 0){
				if(temp_value < min_value){
					min_value = temp_value;
					min_value_comp = <?php echo $offerResponse['COMPANY_ID'];?>;
				}
			}
			$('#offer_row_'+<?php echo $offerResponse['COMPANY_ID'];?>).css("background-color","");//remove css
			writeToOfferRow(<?php echo json_encode($offerResponse); ?>);
		<?php }?>
		if(min_value_comp > 0){
			$('#offer_row_'+min_value_comp).css("background-color","#00FF66");//paint to green
		}
		//pull new offers
		pullOffers(<?php echo $offerRequest[OfferRequest::ID];?>);
	</script>
</div>
<script type="text/javascript">
	$('#branch_2').addClass("active");
</script>
</body>