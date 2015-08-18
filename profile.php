<head>
	<?php include_once (__DIR__.'/head.php'); ?>
</head>
<body>
	<?php
		include_once (__DIR__.'/Util/init.php');
		include_once (__DIR__.'/navigationBar.php');
		
		if(isset($_GET['user_id'])){
			$user_id = urlencode($_GET['user_id']);
		}else{
			$user_id = null;
		}
		
		$loggedInUser = Session::get(Session::USER);
		if(is_null($user_id)){
			$user = $loggedInUser;
		}else{
			$userService = new UserService();
			$user = $userService->getUser($user_id);
		}
		
		$role_name = "TANIMSIZ";
		switch ($user[User::ROLE]) {
			case 1: $role_name="Admin"; break;
			case 2: $role_name="Teknik"; break;
			case 3: $role_name="Acente"; break;
			case 4: $role_name="Finans"; break;
		}
		
		if(!empty($_POST)){
			$agentService = new AgentService();
			$agentService->update(array(
					Agent::USER_ID => Util::cleanInput($_POST['user_id']),
					Agent::EXECUTIVE => Util::cleanInput($_POST['executive']),
					Agent::ADDRESS => Util::cleanInput($_POST['address']),
					Agent::PHONE => Util::cleanInput($_POST['phone']),
					Agent::FAX => Util::cleanInput($_POST['fax']),
					Agent::GSM => Util::cleanInput($_POST['gsm']),
					Agent::EMAIL => Util::cleanInput($_POST['email']),
					Agent::IBAN => Util::cleanInput($_POST['iban']),
					Agent::BANK => Util::cleanInput($_POST['bank']),
					Agent::AGENTS => Util::cleanInput($_POST['agents'])
			));
			if($loggedInUser[User::ID] == $user[User::ID]){//user update him/herself
				$loggedInUser[User::FIRST_LOGIN] = 0;
				Session::put(Session::USER, $loggedInUser);
			}
			//Then check, error occur because of user object taken before post action
			//this is fix it
			$user[User::FIRST_LOGIN] = 0;
			
			?>
			<div align="center">
				<div class="alert alert-success" role="alert">Acente <?php echo $user[User::NAME]; ?> bilgileri başarıyla güncellendi</div>
			</div>
			<?php
		}
		
		if(($loggedInUser[User::ROLE] == User::BRANCH || ($user[User::ROLE] == User::BRANCH && $loggedInUser[User::ROLE] == User::ADMIN)) && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){?>
			<div align="center">
				<div class="alert alert-danger" role="alert">Kullanıcı bilgileri eksiktir, lütfen tamamlayınız!</div>
			</div>
		<?php }
	?>
	
	<div class="container profile-well">
		<div class="well well-lg">
			<h2 class="form-signin-heading">Kullanıcı Bilgileri</h2>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Kod</span>
				<label class="form-control"><?php echo $user[User::CODE]; ?></label>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Kullanıcı adı</span>
				<label class="form-control"><?php echo $user[User::NAME]; ?></label>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Hesap türü</span>
				<label class="form-control"><?php echo $role_name; ?></label>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Oluşturulma tarihi</span>
				<label class="form-control"><?php echo DateUtil::format($user[User::CREATION_DATE]); ?></label>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Ek bilgi</span>
				<textarea rows="4" class="form-control" readonly><?php echo $user[User::DESCRIPTION]; ?></textarea>
			</div>
			<br>
			<?php if($loggedInUser[User::ROLE] == User::BRANCH || ($user[User::ROLE] == User::BRANCH && $loggedInUser[User::ROLE] == User::ADMIN)){ ?>
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Komisyon oranı %</span>
					<label class="form-control"><?php echo $user[User::KOMISYON_RATE]; ?></label>
				</div>
				<br>
				<form class="form-signin" id="agent_detail" action="" method="post" autocomplete="off">
					<h2 class="form-signin-heading">Acente Bilgileri</h2>
					<label class="login-error" id="executive-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Yetkili İsmi</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="executive" name="executive">
					</div>
					<label class="login-error" id="address-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Adres</span>
						<textarea rows="4" cols="30" class="form-control" aria-describedby="basic-addon1" id="address" name="address"></textarea>
					</div>
					<label class="login-error" id="phone-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Telefon</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="phone" name="phone" placeholder="0xxxxxxxxxx">
					</div>
					<label class="login-error" id="fax-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Fax</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="fax" name="fax" placeholder="0xxxxxxxxxx">
					</div>
					<label class="login-error" id="gsm-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Cep telefonu</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="gsm" name="gsm" placeholder="05xxxxxxxxx">
					</div>
					<label class="login-error" id="email-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">E-posta</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="email" name="email">
					</div>
					<label class="login-error" id="iban-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">IBAN</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="iban" name="iban">
					</div>
					<label class="login-error" id="bank-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Banka adı</span>
						<input type="text" class="form-control" aria-describedby="basic-addon1" id="bank" name="bank">
					</div>
					<label class="login-error" id="agents-error"></label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Acentelikleri</span>
						<textarea rows="4" cols="30" class="form-control" aria-describedby="basic-addon1" id="agents" name="agents"></textarea>
					</div>
					<br>
					<label class="login-error" id="terms-error"></label>
					<div>
						<input type="checkbox" id="accept_terms" name="accept_terms">
						<a href="/positive/usage.pdf" target="_blank">Sözleşme şartlarını</a> okudum ve kabul ediyorum
					</div>
					<br>
					<input type="hidden" id="user_id" name="user_id">
					<button class="btn btn-lg btn-primary btn-block" type="button" id="agent_button" onclick="validateAgentForm()">Güncelle</button>
				</form>
			<?php } ?>
			<?php if($loggedInUser[User::ID] == $user[User::ID]){ ?>
				<button class="btn btn-default" type="button" onclick="location.href = '/positive/password.php'">Şifre Değiştir</button>
			<?php } ?>
		</div>
	</div>
	<?php if($loggedInUser[User::ROLE] == User::BRANCH || ($user[User::ROLE] == User::BRANCH && $loggedInUser[User::ROLE] == User::ADMIN)){?>
		<?php 
			$agentService = new AgentService();
			$agent_user = $agentService->get($user[User::ID]);
		?>
		<script src="/positive/js/iban.js"></script>
		<script src="/positive/js/agent_detail.js"></script>
		<script type="text/javascript">
			$('#user_id').val(<?php echo $user[User::ID]; ?>);
			<?php if(!is_null($agent_user)){?>
					$('#executive').val('<?php echo $agent_user[Agent::EXECUTIVE];?>');
					$('#address').val('<?php echo $agent_user[Agent::ADDRESS];?>');
					$('#phone').val('<?php echo $agent_user[Agent::PHONE];?>');
					$('#fax').val('<?php echo $agent_user[Agent::FAX];?>');
					$('#gsm').val('<?php echo $agent_user[Agent::GSM];?>');
					$('#email').val('<?php echo $agent_user[Agent::EMAIL];?>');
					$('#iban').val('<?php echo $agent_user[Agent::IBAN];?>');
					$('#bank').val('<?php echo $agent_user[Agent::BANK];?>');
					$('#agents').val('<?php echo $agent_user[Agent::AGENTS];?>');
					$('#accept_terms').prop("checked", true);
			<?php }?>
		</script>
	<?php }?>
<script type="text/javascript">
	$('#admin_1').addClass("active");
</script>
</body>