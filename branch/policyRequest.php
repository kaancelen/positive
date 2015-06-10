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
	
	$offerId = null;
	if(isset($_GET['offer_id'])){
		$offerId = Util::cleanInput($_GET['offer_id']);
	}
	if(empty($offerId)){
		Util::redirect("/positive/error/404.php");		
	}
	
	$offerService = new OfferService();
	$companyService = new CompanyService();
	$offer = $offerService->getOffer($offerId);
	$request = $offerService->getOfferRequest($offer[OfferResponse::REQUEST_ID]);
	$company = $companyService->getCompany($offer[OfferResponse::COMPANY_ID]);
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
				<td><?php echo $request[OfferRequest::ID];?></td>
				<td><?php echo $request[OfferRequest::CREATION_DATE];?></td>
				<td><?php echo $request[OfferRequest::PLAKA];?></td>
				<td><?php echo $request[OfferRequest::TCKN];?></td>
				<td><?php echo $request[OfferRequest::VERGI];?></td>
				<td><?php echo $request[OfferRequest::BELGE];?></td>
				<td><?php echo $request[OfferRequest::ASBIS];?></td>
			</tr>
		</tbody>
	</table>
	<br>
	<table class="offer-request-info-table">
		<thead>
			<tr>
				<td>Teklif No</td>
				<td>Prim</td>
				<td>Komisyon</td>
				<td>Şirket</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $offer[OfferResponse::ID];?></td>
				<td><?php echo $offer[OfferResponse::PRIM];?></td>
				<td><?php echo $offer[OfferResponse::KOMISYON];?></td>
				<td><?php echo $company[Company::NAME];?></td>
			</tr>
		</tbody>
	</table>
</div>
<br>
<div class="container profile-well">
	<div class="well well-lg">
		<h2 class="form-signin-heading">Kredi kartı bilgileri</h2>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">Kart üzerindeki isim</span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="name" name="name">
		</div>
		<br>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">Kart numarası</span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="card" name="card">
		</div>
		<br>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">Son kullanma tarihi</span>
			<select class="form-control" id="expireMonth" name="expireMonth">
				<?php for ($i = 1; $i <= 12; $i++){ ?>
			    	<option><?php echo $i; ?></option>
			    <?php } ?>
			</select>
			<select class="form-control" id="expireYear" name="expireYear">
				<?php for ($i = 2015; $i <= 2050; $i++){ ?>
			    	<option><?php echo $i; ?></option>
			    <?php } ?>
			</select>
		</div>
		<br>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">Cvc kodu</span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="cvc" name="cvc">
		</div>
		<br>
	</div>
</div>

</body>