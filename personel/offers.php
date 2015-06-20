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
	$allOfferRequest = $offerService->getAllRequests(null, 1);//Tüm kullanıcıların poliçe isteği yapılmamış taleplerini getir.
	
	if(empty($allOfferRequest)){
		?>
			<div id="user_table_msg" align="center">
				<div class="alert alert-warning" role="alert">Hiç talep bulunmamaktadır.</div>
			</div>
		<?php
	}
?>
<div class="container">
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Talep No</b></td>
					<td><b>Kullanıcı Adı</b></td>
					<td><b>İstek Tarihi</b></td>
					<td><b>Plaka</b></td>
					<td><b>TC Kimlik No</b></td>
					<td><b>Vergi No</b></td>
					<td><b>Belge No</b></td>
					<td><b>ASBİS No</b></td>
					<td><b>Aç</b></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
			<?php $userService = new UserService(); ?>
			<?php foreach ($allOfferRequest as $offerRequest){ ?>
				<?php $tempUser = $userService->getUser($offerRequest[OfferRequest::USER_ID]);?>
				<tr>
					<td><?php echo $offerRequest[OfferRequest::ID]; ?></td>
					<td><?php echo $tempUser[User::NAME]; ?></td>
					<td><?php echo $offerRequest[OfferRequest::CREATION_DATE]; ?></td>
					<td><?php echo $offerRequest[OfferRequest::PLAKA]; ?></td>
					<td><?php echo $offerRequest[OfferRequest::TCKN]; ?></td>
					<td><?php echo $offerRequest[OfferRequest::VERGI]; ?></td>
					<td><?php echo $offerRequest[OfferRequest::BELGE]; ?></td>
					<td><?php echo $offerRequest[OfferRequest::ASBIS]; ?></td>
					<td>
						<button id="remove_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/personel/offer.php?request_id=<?php echo urldecode($offerRequest[OfferRequest::ID]);?>';">
						  <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
						</button>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$('#personel_1').addClass("active");
</script>
</body>