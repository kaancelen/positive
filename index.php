<head>
<?php 
	include_once(__DIR__.'/head.php'); 
?>
</head>
<body>
	<div class="container login_form">
		<form class="form-signin" id="positive_login" action="login_action.php" method="post">
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