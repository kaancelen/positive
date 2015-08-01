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
	$userService = new UserService();
	$tempUser = $userService->getUser($offerRequest[OfferRequest::USER_ID]);
	
	$makePolicyPermission = true;
	if($offerRequest[OfferRequest::IS_OFFER_ACCEPTED]){
		$makePolicyPermission = false;
		?>
		<div id="user_form_msg" align="center">
			<div class="alert alert-info" role="alert">Bu talep için bir teklif kabul edilmiş ve poliçe isteklerine eklenmiş.</div>
		</div>
		<?php
	}
?>
<script src="/positive/js/personel.js"></script>
<div class="well offer-request-label">
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
					<td>ASBİS No</td>
				<?php } ?>
				<td>Ek Bilgi</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $offerRequest[OfferRequest::ID];?></td>
				<td><?php echo $offerRequest[OfferRequest::POLICY_TYPE];?></td>
				<td><?php echo $tempUser[User::NAME];?></td>
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
						<td><b>Sigorta şirketi</b></td>
						<td><b>Prim</b></td>
						<td><b>Komisyon</b></td>
						<td><b>Prod Komisyonu</b></td>
						<td><b>Teklif ver</b></td>
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
	<!-- input mask -->
	<script type="text/javascript">
		<?php foreach ($offerRequest[OfferRequest::COMPANIES] as $company){?>
			$('#prim_<?php echo $company[Company::ID]; ?>').mask('000.000.000.000.000,00', {reverse: true});
			$('#komisyon_<?php echo $company[Company::ID]; ?>').mask('000.000.000.000.000,00', {reverse: true});
			$('#komisyon_<?php echo $company[Company::ID]; ?>').keyup(function() {
				var komisyon = $(this).val();
				komisyon = komisyon.replace('.', '').replace(',', '.');
				var prod_komisyon = (komisyon * <?php echo $tempUser[User::KOMISYON_RATE]; ?>) / 100;
				$('#prod_komisyon_<?php echo $company[Company::ID]; ?>').val(prod_komisyon);
			});
		<?php }?>
		<?php foreach($offerResponses as $offerResponse){?>
			disableOfferRow(<?php echo json_encode($offerResponse); ?>);
		<?php }?>
	</script>
</div>
<script type="text/javascript">
	$('#personel_1').addClass("active");
	pullOfferPageControl(<?php echo $offerRequest[OfferRequest::ID]; ?>);
</script>
</body>