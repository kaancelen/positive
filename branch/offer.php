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
	if(is_null($offerRequestId)){
		Util::redirect('/positive/error/404.php');
	}
	
	$offerService = new OfferService();
	$offerRequest = $offerService->getOfferRequest($offerRequestId);
	if(is_null($offerRequest)){
		Util::redirect('/positive/error/404.php');
	}
	
	$offerResponses = $offerService->getOffers($offerRequestId);
	$userService = new UserService();
?>
<script src="/positive/js/branch.js"></script>
<div class="well offer-request-label">
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Talep No</td>
				<td>İstek tarihi</td>
				<td>Plaka</td>
				<td>TC Kimlik No</td>
				<td>Vergi No</td>
				<td>Belge No</td>
				<td>ASBİS No</td>
				<td>Ek Bilgi</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $offerRequest[OfferRequest::ID];?></td>
				<td><?php echo $offerRequest[OfferRequest::CREATION_DATE];?></td>
				<td><?php echo $offerRequest[OfferRequest::PLAKA];?></td>
				<td><?php echo $offerRequest[OfferRequest::TCKN];?></td>
				<td><?php echo $offerRequest[OfferRequest::VERGI];?></td>
				<td><?php echo $offerRequest[OfferRequest::BELGE];?></td>
				<td><?php echo $offerRequest[OfferRequest::ASBIS];?></td>
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
						<td><b>Poliçeleştir</b></td>
					</tr>
					<tr>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($offerRequest[OfferRequest::COMPANIES] as $company){ ?>
					<tr>
						<td id="offer_id_<?php echo $company[Company::ID]; ?>"></td>
						<td id="personel_id_<?php echo $company[Company::ID]; ?>"></td>
						<td><?php echo $company[Company::NAME]; ?></td>
						<td style="width:20%">
							<div class="input-group input-group-sm">
							  <span class="input-group-addon">₺</span>
							  <input type="text" readonly="readonly" class="form-control input-tl" id="prim_<?php echo $company[Company::ID]; ?>">
							</div>
						</td>
						<td style="width:20%">
							<div class="input-group input-group-sm">
							  <span class="input-group-addon">₺</span>
							  <input type="text" readonly="readonly" class="form-control input-tl" id="komisyon_<?php echo $company[Company::ID]; ?>">
							</div>
						</td>
						<td>
							<button id="make_policy_<?php echo $company[Company::ID]; ?>" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
								onclick="navigateToPolicyRequest(<?php echo $company[Company::ID]; ?>);" style="display: none">
							  <span class="glyphicon glyphicon-paste" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="well chat-column">
		<h4 style="text-align:center">Konuşma</h4>
		<hr>	
	</div>
	<script type="text/javascript">
		<?php foreach($offerResponses as $offerResponse){?>
			<?php $personelUser = $userService->getUser($offerResponse[OfferResponse::USER_ID]);?>
			writeToOfferRow(<?php echo json_encode($offerResponse); ?>, '<?php echo $personelUser[User::NAME];?>');
		<?php }?>
	</script>
</div>
</body>