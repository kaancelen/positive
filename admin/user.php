<!-- ADMIN -->
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
		if($user[User::ROLE] != User::ADMIN){
			Util::redirect("/positive/error/403.php");
		}
	}
	include_once (__DIR__.'/../navigationBar.php');
	
	$operation = null;
	$user_id = null;
	$selected_user = null;
	$post_message = null;
	$post_flag = null;
	$userService = new UserService();
	if(isset($_GET['operation'])){
		$operation = Util::cleanInput(urlencode($_GET['operation']));
	}
	if(isset($_GET['user_id'])){
		$user_id = Util::cleanInput(urlencode($_GET['user_id']));
		$selected_user = $userService->getUser($user_id);
	}
	
	if(!empty($_POST)){
		$username = Util::cleanInput($_POST['username']);
		$name = Util::cleanInput($_POST['name']);
		$email = Util::cleanInput($_POST['email']);
		$role = Util::cleanInput($_POST['select_role']);
		$phone = Util::cleanInput($_POST['phone']);
		$desc = Util::cleanInput($_POST['description']);
		$operation = Util::cleanInput($_POST['operation']);
		
		if($operation == 'add'){
			$logger->write(ALogger::INFO, __FILE__, "user add operation to [".$username."] by [".$user[User::CODE]."]");
			$password = $username;
			$result = $userService->addUser($name, $email, $username, $password, $role, $phone, $desc);
			if($result == null){
				$post_flag = 0;
				$post_message = "Kullanıcı ekleme işlemi başarısız, kullanıcı adı mevcut!";
			}else if(!$result){
				$post_flag = 0;
				$post_message = "Kullanıcı ekleme işlemi başarısız";
			}else{
				Session::flash(Session::FLASH, $username." kullanıcısı başarı ile eklendi.");
				Util::redirect("/positive/admin/users.php");
			}
		}else if($operation == 'edit'){
			$logger->write(ALogger::INFO, __FILE__, "user edit to operation to [".$selected_user[User::CODE]."] by [".$user[User::CODE]."]");
			$result = $userService->updateUser($user_id, $name, $email, $role, $phone, $desc);
			if($result == null){
				$post_flag = 0;
				$post_message = "Kullanıcı düzenleme işlemi başarısız, kullanıcı adı mevcut!";
			}else if(!$result){
				$post_flag = 0;
				$post_message = "Kullanıcı [".$username."] düzenleme işlemi başarısız";
			}else{
				$post_flag = 1;
				$post_message = "Kullanıcı [".$username."] başarı ile düzenlendi.";
				$selected_user = $userService->getUser($user_id);
			}
		}
	}
?>

<div id="user_form_msg" align="center">
	<div class="alert alert-danger" role="alert" id="user_form_error" style="visibility: hidden;"></div>
	<div class="alert alert-success" role="alert" id="user_form_success" style="visibility: hidden;"></div>
</div>
<div class="container user_form">
	<div class="well well-lg">
		<form class="form-signin" id="positive_user" action="" method="post" autocomplete="off">
			<label class="login-error" id="user-error"></label>
	        <label for="username" class="sr-only">Kullanıcı Adı</label>
	        <input type="text" id="username" name="username" class="form-control" placeholder="Kullanıcı Adı" autofocus>
	        <br>
	        <label for="name" class="sr-only">İsim</label>
	        <input type="text" id="name" name="name" class="form-control" placeholder="İsim" autofocus>
	        <br>
	        <label for="email" class="sr-only">E-Posta</label>
	        <input type="text" id="email" name="email" class="form-control" placeholder="E-Posta" autofocus>
	        <br>
	        <label for="phone" class="sr-only">Telefon</label>
	        <input type="text" id="phone" name="phone" class="form-control" placeholder="Telefon" autofocus>
	        <br>
	        <select id="select_role" name="select_role" class="form-control">
				<option value="0">Rol Seçiniz</option>
				<option value="1">Admin</option>
				<option value="2">Personel</option>
				<option value="3">Acente</option>
			</select>
			<br>
			<label for="description" class="sr-only">Telefon</label>
			<textarea rows="4" id="description" name="description" class="form-control" placeholder="Ek Bilgi" autofocus></textarea>
	        <br>
	        <input type="hidden" id="user_id" name="user_id">
	        <input type="hidden" id="operation" name="operation">
	        <button class="btn btn-lg btn-primary btn-block" type="button" id="update_button"
	        	onclick="validateUserForm()">
	        	<?php 
	        		if(!is_null($selected_user)){
	        			echo "Güncelle";
	        		}else{
	        			echo "Ekle";
	        		}
	        	?>
	        </button>
	     </form>
	     <?php if(!is_null($selected_user)){?>
	     <br>
	     <button type="button" class="btn btn-default" aria-label="Left Align"
	     	onclick="resetPassword(<?php echo $selected_user[User::ID];?>, '<?php echo $selected_user[User::CODE]; ?>')">
		 	<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>Şifre sıfırla
		 </button>
		 <?php }?>
      </div>
</div>
<script src="../js/userPage.js"></script>
<script type="text/javascript">
<?php
	echo '$("#operation").val("'.$operation.'");';
	if(!is_null($selected_user)){
		echo '$("#username").prop("readonly", true);';
		echo 'fillUserForm('.json_encode($selected_user).');';
	}
	
	if(!is_null($post_flag)){
		echo 'showPostMessage('.$post_flag.',"'.$post_message.'");';
	}
	if(!empty($_POST)){
		echo "$('#username').val('".$username."');";
		echo "$('#name').val('".$name."');";
		echo "$('#email').val('".$email."');";
		echo "$('#phone').val('".$phone."');";
		echo "$('#description').val('".$desc."');";
		echo "$('#select_role').val(".$role.");";
	}
?>
</script>

<body>