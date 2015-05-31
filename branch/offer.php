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
?>
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
						<td><b>Prodüktör komisyonu</b></td>
						<td><b>Poliçeleştir</b></td>
					</tr>
					<tr>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($offerRequest[OfferRequest::COMPANIES] as $company){ ?>
					<tr>
						<td></td>
						<td><?php echo $company[Company::NAME]; ?></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							<button id="remove_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
								onclick="location.href = '/positive/branch';">
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
</div>
</body>