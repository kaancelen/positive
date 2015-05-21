<head>
	<meta http-equiv="Content-Type" content="text/HTML; charset=UTF-8"/>
	<link rel="icon" type="image/ico" href="images/favicon.ico">
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="js/jquery/jquery.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="css/bootstrap/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<?php 
		include_once (__DIR__.'/Util/util.php');
		include_once (__DIR__.'/service/LoginService.php');
		if(!empty($_POST)){
			$loginService = new LoginService();
			
			$username = Util::cleanInput($_POST['username']);
			$password = Util::cleanInput($_POST['password']);
			$remember = isset($_POST['remember']) ? true : false;
			
			$db_output = $loginService->login($username, $password, $remember);
			if($db_output['role'] == 0){
				echo "NOOO";
			}else if($db_output['role'] == 1){
				Util::redirect("/positive/admin");
			}else if($db_output['role'] == 2){
				Util::redirect("/positive/personel");
			}else if($db_output['role'] == 3){
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
</body>