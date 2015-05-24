<head>
	<?php include_once (__DIR__.'/headIndex.php'); ?>
</head>
<body>
	<?php
		include_once (__DIR__.'/Util/init.php');
		include_once (__DIR__.'/navigationBar.php');
		
		$password_change_flag = null;
		$password_change_message = null;
		if(!empty($_POST)){
			$user = Session::get(Session::USER);
			$oldPassword = Util::cleanInput($_POST['oldPassword']);
			$newPassword = Util::cleanInput($_POST['newPassword']);
			$newPasswordAgain = Util::cleanInput($_POST['newPasswordAgain']);
			
			$logger->write(ALogger::INFO, __FILE__, "password change request [".$user[User::CODE]."]");
			
			if($newPassword != $newPasswordAgain){
				$password_change_flag = false;
				$password_change_message = "Yeni şifre, tekrarı ile eşleşmiyor.";
			}else if(strlen($newPassword) < 8){
				$password_change_flag = false;
				$password_change_message = "Şifre en az 8 karakterli olmalıdır.";
			}else{
				$loginService = new LoginService();
				$result = $loginService->login($user[User::CODE], $oldPassword);
				if($result[User::ROLE] < 1){
					$password_change_flag = false;
					$password_change_message = "Eski şifre geçersiz.";
				}else{
					$salt = Hash::unique();
					$hash = Hash::make($newPassword, $salt);
					$userService = new UserService();
					$result = $userService->changePassword($user[User::ID], $salt, $hash);
					if(!$result){
						$password_change_flag = false;
						$password_change_message = "Şifre değiştirilirken bir hata oluştu.";
						$logger->write(ALogger::INFO, __FILE__, "password could not changed [".$user[User::CODE]."]");
					}else{
						$password_change_flag = true;
						$password_change_message = "Şifre başarıyla değiştirildi.";
						$logger->write(ALogger::INFO, __FILE__, "password changed [".$user[User::CODE]."]");
					}
				}
			}
		}
		
	?>
	<div class="container profile-well">
		<div class="well well-lg">
			<form action="" method="post" id="password_change_form" autocomplete="off">
		        <label class="login-error" id="password_change_error"></label>
		        <label class="success-label" id="password_change_success"></label>
		        <label for="password" class="sr-only">Eski şifre</label>
		        <input type="password" id="oldPassword" name="oldPassword" class="form-control" placeholder="Eski şifre">
		        <br>
		        <label for="password" class="sr-only">Yeni şifre</label>
		        <input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="Yeni şifre">
		        <br>
		        <label for="password" class="sr-only">Yeni şifre tekrar</label>
		        <input type="password" id="newPasswordAgain" name="newPasswordAgain" class="form-control" placeholder="Yeni şifre tekrar">
		        <br>
		        <button class="btn btn-lg btn-primary btn-block" type="button" id="password_change_button">Değiştir</button>
			</form>
		</div>
	</div>
	<?php 
		if(!empty($_POST) && !is_null($password_change_flag)){
			echo '<script type="text/javascript">';
			if($password_change_flag){
				echo '$("#password_change_success").html("'.$password_change_message.'");';
			}else{
				echo '$("#password_change_error").html("'.$password_change_message.'");';
			}
			echo '</script>';
		}
	?>
	<script type="text/javascript">
		$('#password_change_button').on('click', function(){
			var oldPassword = $('#oldPassword').val();
			var newPassword = $('#newPassword').val();
			var newPasswordAgain = $('#newPasswordAgain').val();

			if(oldPassword == null || oldPassword == "" ||
					newPassword == null || newPassword == "" ||
					newPasswordAgain == null || newPasswordAgain == ""){
				$('#password_change_error').html('Tüm şifre kutularını doldurunuz.');
				return;
			}else{
				$('#password_change_error').html('');
			}

			if(newPassword != newPasswordAgain){
				$('#password_change_error').html('Yeni şifre, tekrarı ile eşleşmiyor.');
				return;
			}else{
				$('#password_change_error').html('');
			}

			if(newPassword.length < 8){
				$('#password_change_error').html('Şifre en az 8 karakterli olmalıdır.');
				return;
			}

			var form = $('#password_change_form');
			form.submit();
		});
	</script>
</body>