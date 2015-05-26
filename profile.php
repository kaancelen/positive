<head>
	<?php include_once (__DIR__.'/headIndex.php'); ?>
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
		
		if(is_null($user_id)){
			$user = Session::get(Session::USER);
		}else{
			$userService = new UserService();
			$user = $userService->getUser($user_id);
		}
		
		$role_name = "TANIMSIZ";
		switch ($user[User::ROLE]) {
			case 1: $role_name="Admin"; break;
			case 2: $role_name="Personel"; break;
			case 3: $role_name="Acente"; break;
		}
	?>
	
	<div class="container profile-well">
		<div class="well well-lg">
			<p>Kullanıcı Adı : <?php echo $user[User::CODE]; ?></p>
			<p>Hesap Türü : <?php echo $role_name; ?></p>
			<p>İsim : <?php echo $user[User::NAME]; ?></p>
			<p>E-Posta : <?php echo $user[User::EMAIL]; ?></p>
			<p>Telefon : <?php echo $user[User::PHONE]; ?></p>
			<p>Ek Bilgi : <?php echo $user[User::DESCRIPTION]; ?></p>
			<br><br>
			<?php if(is_null($user_id)){ ?>
				<button class="btn btn-default" type="button" onclick="location.href = '/positive/password.php'">Şifre Değiştir</button>
			<?php } ?>
		</div>
	</div>
</body>