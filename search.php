<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
<?php 
	include_once (__DIR__.'/Util/init.php');
	include_once (__DIR__.'/navigationBar.php');
	include_once (__DIR__.'/classes/search.php');
	
	if($loggedIn){
		$user = Session::get(Session::USER);
		if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
			Util::redirect("/positive/profile.php");
		}
	}
?>
<script src="/positive/js/policySearch.js"></script>
<div class="container offer-request-screen">
	<div class="search-column">
		<label class="login-error" id="request-search-error"></label>
		<div class="input-group" style="width: 80%">
			<span class="input-group-addon" id="basic-addon1">Talep No : </span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="request_id" name="request_id">
			<span class="input-group-btn">
	        	<button id="request_search" class="btn btn-primary" type="button" onclick="requestSearch()">Ara</button>
	      	</span>
		</div>
		<label class="login-error" id="offer-search-error"></label>
		<div class="input-group" style="width: 80%">
			<span class="input-group-addon" id="basic-addon1">Teklif No : </span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="offer_id" name="offer_id">
			<span class="input-group-btn">
	        	<button id="offer_search" class="btn btn-primary" type="button" onclick="offerSearch()">Ara</button>
	      	</span>
		</div>
		<label class="login-error" id="policy-search-error"></label>
		<div class="input-group" style="width: 80%">
			<span class="input-group-addon" id="basic-addon1">Poliçe No : </span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="policy_no" name="policy_no">
			<span class="input-group-btn">
	        	<button id="policy_search" class="btn btn-primary" type="button" onclick="policySearch()">Ara</button>
	      	</span>
		</div>
		<br>
		<label class="login-error" id="extend-search-error"></label>
		<br>
		<div class="input-group" style="width: 80%">
			<span class="input-group-addon" id="basic-addon1">Plaka No : </span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="plaka_no" name="plaka_no">
		</div>
		<br>
		<div class="input-group" style="width: 80%">
			<span class="input-group-addon" id="basic-addon1">TC Kimlik No : </span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="tckn" name="tckn">
		</div>
		<br>
		<div class="input-group" style="width: 80%">
			<span class="input-group-addon" id="basic-addon1">Vergi No : </span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="vergi_no" name="vergi_no">
		</div>
		<br>
		<div class="input-group" style="width: 80%">
			<span class="input-group-addon" id="basic-addon1">Belge No : </span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="belge_no" name="belge_no">
		</div>
		<br>
		<div class="input-group" style="width: 80%">
			<span class="input-group-addon" id="basic-addon1">ASBIS : </span>
			<input type="text" class="form-control" aria-describedby="basic-addon1" id="asbis" name="asbis">
		</div>
		<br>
		<button id="extend_search" class="form-control btn btn-primary" style="width: 80%" type="button" onclick="extendSearch()">Ara</button>
	</div>
	<div class="result-column">
		<div class="well">
			<h4>Talepler</h4>
			<label class="login-error" id="request-part-error"></label>
			<div id="request_part">
			</div>
		</div>
		<div class="well">
			<h4>Poliçe istekleri</h4>
			<label class="login-error" id="offer-part-error"></label>
			<div id="offer_part">
			</div>
		</div>
		<div class="well">
			<h4>Poliçeler</h4>
			<label class="login-error" id="policy-part-error"></label>
			<div id="policy_part">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#search_1').addClass("active");
</script>
</body>