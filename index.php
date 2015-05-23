<head>
	<?php include_once (__DIR__.'/headIndex.php'); ?>
</head>
<body>
	<?php
		include_once (__DIR__.'/Util/init.php');
		
		if($loggedIn){
			$user = Session::get(Session::USER);
			//redirect
			if($user[User::ROLE] == User::ADMIN){
				Util::redirect("/positive/admin");
			}else if($user[User::ROLE] == User::PERSONEL){
				Util::redirect("/positive/personel");
			}else if($user[User::ROLE] == User::BRANCH){
				Util::redirect("/positive/branch");
			}
		}
		
		if(!empty($_POST)){
			$loginService = new LoginService();
			
			$username = Util::cleanInput($_POST['username']);
			$password = Util::cleanInput($_POST['password']);
			$remember = isset($_POST['remember']) ? true : false;
			
			$logger->write(ALogger::INFO, __FILE__, "Login request come [".$username."]");
			$user = $loginService->login($username, $password);
			//put to session
			if($user[User::ROLE] > 0){
				$logger->write(ALogger::INFO, __FILE__, "Logged in [".$username."]");
				Session::put(Session::USER, $user);
				if($remember){
					$hash = Hash::unique();
					Cookie::put(Cookie::HASH, $hash, Cookie::REMEMBER_EXPIRE);
					$loginService->remember($user[User::ID], $hash);
				}
			}
			//redirect
			if($user[User::ROLE] == User::ADMIN){
				Util::redirect("/positive/admin");
			}else if($user[User::ROLE] == User::PERSONEL){
				Util::redirect("/positive/personel");
			}else if($user[User::ROLE] == User::BRANCH){
				Util::redirect("/positive/branch");
			}
		}
	?>
	<div class="container login_form">
		<form class="form-signin" id="positive_login" action="" method="post">
	        <h2 class="form-signin-heading">
	        	<img src="images/positive.png" class="login_image">&nbsp&nbspGiriş Yapınız
	        </h2>
	        <label for="username" class="login-error" id="login-error"></label>
	        <label for="username" class="sr-only">Kullanıcı Adı</label>
	        <input type="text" id="username" name="username" class="form-control" placeholder="Kullanıcı Adı" autofocus>
	        <label for="password" class="sr-only">Şifre</label>
	        <input type="password" id="password" name="password" class="form-control" placeholder="Şifre">
	        <div class="checkbox">
	          <label>
	            <input type="checkbox" id="remember" name="remember" value="remember-me"> Beni Hatırla
	          </label>
	        </div>
	        <button class="btn btn-lg btn-primary btn-block" type="button" id="login_button">Giriş</button>
      </form>
	</div>
	<script src="js/login.js"></script>
	
	<?php 
		if(!empty($_POST)){
			echo "<script type='text/javascript'>";
			if($user[User::ROLE] == User::USER_NOT_FOUND){
				echo "$('#login-error').html('Böyle bir kullanıcı adı kayıtlı değil.');";
			}else if($user[User::ROLE] == User::WRONG_PASS){
				echo "$('#login-error').html('Kullanıcı adı-şifre hatalı.');";
			}
			echo "</script>";
		}
	?>
</body>