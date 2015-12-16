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
	$agentService = new AgentService();
	$agentRelation = $agentService->getAgentRelation($offerRequest[OfferRequest::USER_ID]);
	$userService = new UserService();
	$agentUser = $userService->getUser($offerRequest[OfferRequest::USER_ID]);
	
	$makePolicyPermission = true;
	$alertText = "";
	if($offerRequest[OfferRequest::IS_OFFER_ACCEPTED]){
		$makePolicyPermission = false;
		$alertText = "Bu talep için bir teklif kabul edilmiş ve poliçe isteklerine eklenmiş.";
		
	}
	if($offerRequest[OfferRequest::STATUS] == 2){
		$makePolicyPermission = false;
		$alertText = "Bu talep kapatılmıştır, yeni teklif verilemez.";
	}
	if(!$makePolicyPermission){
		?>
		<div id="user_form_msg" align="center">
			<div class="alert alert-warning" role="alert"><?php echo $alertText; ?></div>
		</div>
		<?php
	}
	
	$reopen_request = false;
	$two_days_ago = strtotime("-2 days", time());
	$offer_timestamp = strtotime($offerRequest[OfferRequest::CREATION_DATE]);
	if($two_days_ago > $offer_timestamp){
		$reopen_request = true;
	}
	
	$genericService = new GenericService();
	$genericService->updateUserEnter($user[User::ID], $offerRequestId, 0);
?>
<script src="/positive/js/personel.js"></script>
<script src="/positive/js/closeRequest.js"></script>
<script src="/positive/js/open_request.js"></script>
<div class="well offer-request-label">
	<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModel">
		<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Düzelt
	</button>
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Talep No</td>
				<td>Poliçe</td>
				<td>Kullanıcı Adı</td>
				<td>İstek tarihi</td>
				<?php if($offerRequest[OfferRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
					<td>Plaka</td>
					<td>TC Kimlik No</td>
					<td>Vergi No</td>
					<td>Belge No</td>
					<td>ASBİS</td>
					<td>Marka Kodu</td>
				<?php } ?>
				<?php if($reopen_request){?>
					<td>Yeniden aç</td>
				<?php }else{ ?>
					<td>Talebi kapat</td>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $offerRequest[OfferRequest::ID];?></td>
				<td><h3 style="color: red;"><?php echo $offerRequest[OfferRequest::POLICY_TYPE];?><h3></td>
				<td><?php echo $agentUser[User::NAME];?></td>
				<td><?php echo DateUtil::format($offerRequest[OfferRequest::CREATION_DATE]);?></td>
				<?php if($offerRequest[OfferRequest::POLICY_TYPE] != PolicyType::DIGER){ ?>
					<td><?php echo $offerRequest[OfferRequest::PLAKA];?></td>
					<td><?php echo $offerRequest[OfferRequest::TCKN];?></td>
					<td><?php echo $offerRequest[OfferRequest::VERGI];?></td>
					<td><?php echo $offerRequest[OfferRequest::BELGE];?></td>
					<td><?php echo $offerRequest[OfferRequest::ASBIS];?></td>
					<td><?php echo $offerRequest[OfferRequest::MARKA_KODU];?></td>
				<?php } ?>
				<?php if($reopen_request){?>
					<td>
						<div style="text-align: center">
							<button type="button" class="btn btn-default btn-lg" onclick="openRequest(<?php echo $offerRequest[OfferRequest::ID];?>);">
								<span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
							</button>
						</div>
					</td>
				<?php }else{ ?>
					<td>
						<div style="text-align: center">
							<button type="button" class="btn btn-default btn-lg" style="color: red" onclick="closeRequest(2,<?php echo $offerRequest[OfferRequest::ID];?>);">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</button>
						</div>
					</td>
				<?php } ?>
			</tr>
		</tbody>
	</table>
	<br>
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Ek Bilgi</td>
			</tr>
		</thead>
		<tbody>
			<tr>
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
						<td><b>Sigorta şirketi</b></td>
						<td><b>Prim</b></td>
						<td><b>Komisyon</b></td>
						<td><b>Prod Komisyonu</b></td>
						<td><b>Teklif ver</b></td>
						<td style="display: none;"></td>
					</tr>
					<tr>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($offerRequest[OfferRequest::COMPANIES] as $company){ ?>
					<tr>
						<td id="offer_id_<?php echo $company[Company::ID]; ?>"></td>
						<td><?php echo $company[Company::NAME]; ?></td>
						<td style="width:15%">
							<div class="input-group input-group-sm">
							  <span class="input-group-addon">₺</span>
							  <input type="text" class="form-control input-tl" id="prim_<?php echo $company[Company::ID]; ?>" name="prim_<?php echo $company[Company::ID]; ?>">
							</div>
						</td>
						<td style="width:15%">
							<div class="input-group input-group-sm">
							  <span class="input-group-addon">₺</span>
							  <input type="text" class="form-control input-tl" id="komisyon_<?php echo $company[Company::ID]; ?>" name="komisyon_<?php echo $company[Company::ID]; ?>">
							</div>
						</td>
						<td style="width:15%">
							<div class="input-group input-group-sm">
							  <span class="input-group-addon">₺</span>
							  <input type="text" readonly="readonly" class="form-control input-tl" id="prod_komisyon_<?php echo $company[Company::ID]; ?>" name="prod_komisyon_<?php echo $company[Company::ID]; ?>">
							</div>
						</td>
						<td>
							<?php if($makePolicyPermission){ ?>
							<button id="give_offer_<?php echo $company[Company::ID]; ?>" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
								onclick="giveOffer(<?php echo $offerRequest[OfferRequest::ID]; ?>,<?php echo $company[Company::ID]; ?>, '<?php echo $company[Company::NAME]; ?>', <?php echo $user[User::ID]; ?>);">
							  <span class="glyphicon glyphicon-paste" aria-hidden="true"></span>
							</button>
							<button id="remove_offer_<?php echo $company[Company::ID]; ?>" type="button" class="btn btn-default btn-sm" aria-label="Left Align" style="display: none"
								onclick="removeOffer(<?php echo $offerRequest[OfferRequest::ID]; ?>, <?php echo $company[Company::ID]; ?>, '<?php echo $company[Company::NAME]; ?>');" >
							  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</button>
							<?php } ?>
						</td>
						<td style="display: none;">
							<input type="hidden" id="ust_komisyon_<?php echo $company[Company::ID]; ?>" name="ust_komisyon_<?php echo $company[Company::ID]; ?>">
							<input type="hidden" id="bagli_komisyon_<?php echo $company[Company::ID]; ?>" name="bagli_komisyon_<?php echo $company[Company::ID]; ?>">
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
	<!-- input mask -->
	<script type="text/javascript">
		<?php foreach ($offerRequest[OfferRequest::COMPANIES] as $company){?>
			$('#prim_<?php echo $company[Company::ID]; ?>').mask('000.000.000.000.000,00', {reverse: true});
			$('#komisyon_<?php echo $company[Company::ID]; ?>').mask('000.000.000.000.000,00', {reverse: true});

			$('#prod_komisyon_<?php echo $company[Company::ID]; ?>').change(function(){
				var prod_komisyon_value = (Number)(this.value);
				this.value = prod_komisyon_value.format(2, 3, '.', ',');
			});
			$('#ust_komisyon_<?php echo $company[Company::ID]; ?>').change(function(){
				var ust_komisyon_value = (Number)(this.value);
				this.value = ust_komisyon_value.format(2, 3, '.', ',');
			});
			$('#bagli_komisyon_<?php echo $company[Company::ID]; ?>').change(function(){
				var bagli_komisyon_value = (Number)(this.value);
				this.value = bagli_komisyon_value.format(2, 3, '.', ',');
			});
			
			$('#komisyon_<?php echo $company[Company::ID]; ?>').keyup(function() {
				var komisyon = $(this).val();
				komisyon = komisyon.replace('.', '').replace(',', '.');
				var prod_komisyon = (komisyon * <?php echo $agentRelation[AgentRelation::KOMISYON]; ?>) / 100;
				$('#prod_komisyon_<?php echo $company[Company::ID]; ?>').val(prod_komisyon).trigger('change');

				var ust_komisyon = (komisyon * <?php echo $agentRelation[AgentRelation::UST_KOMISYON]; ?>) / 100;
				$('#ust_komisyon_<?php echo $company[Company::ID]; ?>').val(ust_komisyon).trigger('change');

				var bagli_komisyon = (komisyon * <?php echo $agentRelation[AgentRelation::BAGLI_KOMISYON]; ?>) / 100;
				$('#bagli_komisyon_<?php echo $company[Company::ID]; ?>').val(bagli_komisyon).trigger('change');
			});
		<?php }?>
		<?php foreach($offerResponses as $offerResponse){?>
			disableOfferRow(<?php echo json_encode($offerResponse); ?>);
		<?php }?>
	</script>
</div>
<?php include_once (__DIR__.'/../offerRequestChangeModel.php');?>
<script type="text/javascript">
	$('#personel_1').addClass("active");
	pullOfferPageControl(<?php echo $offerRequest[OfferRequest::ID]; ?>);
</script>
</body>