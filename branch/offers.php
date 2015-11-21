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
	//policy polling job
	Cookie::put(Cookie::LAST_ENTER_OFFER_RESP, date(DateUtil::DB_DATE_FORMAT_TIME), Cookie::REMEMBER_EXPIRE);//son sayfa yenilemeyi cookie'ye yaz
	Cookie::put(Cookie::LE_OFFER_RESP_FLAG, "off", Cookie::REMEMBER_EXPIRE);
	
	include_once (__DIR__.'/../navigationBar.php');
	if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
		Util::redirect("/positive/profile.php");
	}
	
	$offerService = new OfferService();
	$allOfferRequest = $offerService->getBranchRequests($user[User::ID]);//Bu kullanıcının poliçe isteği yapılmamış taleplerini getir.
	
	if(empty($allOfferRequest)){
		?>
			<div id="user_table_msg" align="center">
				<div class="alert alert-warning" role="alert">Hiç Talebiniz bulunmamaktadır.</div>
			</div>
		<?php
	}
?>
<script src="/positive/js/pullNewChat.js"></script>
<div class="container">
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Durum</b></td>
					<td><b>Talep No</b></td>
					<td><b>Teklif Sayısı</b></td>
					<td><b>Poliçe</b></td>
					<td><b>İstek Tarihi</b></td>
					<td><b>Plaka</b></td>
					<td><b>Aç</b></td>
				</tr>
				<tr>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($allOfferRequest as $offerRequest){ ?>
				<tr <?php if($offerRequest[OfferList::STATUS] == 2) echo "class='row-offer-cancelled'";?>>
					<td id="request_<?php echo $offerRequest[OfferList::ID]; ?>"></td>
					<td><b><?php echo $offerRequest[OfferList::ID]; ?></b></td>
					<td><?php echo $offerRequest[OfferList::OFFER_RATE]; ?></td>
					<td><?php echo $offerRequest[OfferList::POLICY_TYPE]; ?></td>
					<td><?php echo DateUtil::format($offerRequest[OfferList::CREATION_DATE]); ?></td>
					<td><?php echo $offerRequest[OfferList::PLAKA]; ?></td>
					<td>
						<button id="make_policies_button" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/branch/offer.php?request_id=<?php echo urldecode($offerRequest[OfferList::ID]);?>';">
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
	pullNewChatEntries();
	$('#branch_2').addClass("active");
</script>
</body>