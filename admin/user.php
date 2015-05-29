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
		$role = Util::cleanInput($_POST['select_role']);
		$desc = Util::cleanInput($_POST['description']);
		$operation = Util::cleanInput($_POST['operation']);
		
		if($operation == 'add'){
			$logger->write(ALogger::INFO, __FILE__, "user add operation to [".$username."] by [".$user[User::CODE]."]");
			$password = $username;
			$result = $userService->addUser($name, $username, $password, $role, $desc);
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
			$result = $userService->updateUser($user_id, $name, $role, $desc);
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
	        <div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Kod</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="username" name="username">
			</div>
	        <br>
	        <div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Adı</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="name" name="name">
			</div>
	        <br>
	        <div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Adı</span>
				<select id="select_role" name="select_role" class="form-control">
					<option value="0">Rol Seçiniz</option>
					<option value="1">Admin</option>
					<option value="2">Teknik</option>
					<option value="3">Acente</option>
					<option value="4">Finans</option>
				</select>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Adres</span>
				<textarea rows="4" cols="30" class="form-control" aria-describedby="basic-addon1" id="description" name="description"></textarea>
			</div>
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
		echo "$('#description').val('".$desc."');";
		echo "$('#select_role').val(".$role.");";
	}
?>
</script>

<body>