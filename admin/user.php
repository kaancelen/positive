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
	
	$allAgents = $userService->allTypeOfUsers(User::BRANCH);
	
	$companyService = new CompanyService();
	$companies = $companyService->getAll();
	
	if(!empty($_POST)){
		$username = Util::cleanInput($_POST['username']);
		$name = Util::cleanInput($_POST['name']);
		$role = Util::cleanInput($_POST['select_role']);
		$desc = Util::cleanInput($_POST['description']);
		$operation = Util::cleanInput($_POST['operation']);
		
		$komisyon_rate = 0;
		$master_agent = 0;
		$allowed_comp = "0";
		$change_agent = 0;
		
		if($role == User::BRANCH){
			$komisyon_rate = Util::cleanInput($_POST['komisyon_rate']);
			$master_agent = Util::cleanInput($_POST['master_agent']);
			
			if(isset($_POST['comp_0'])){
				$allowed_comp = "0";
			}else{
				$allowed_comp_array = array();
				foreach ($companies as $company){
					if(isset($_POST['comp_'.$company[Company::ID]])){
						array_push($allowed_comp_array, $company[Company::ID]);
					}
				}
				$allowed_comp = implode(",", $allowed_comp_array);
			}
		}else if($role == User::PERSONEL){
			
			if(isset($_POST['comp_0'])){
				$allowed_comp = "0";
			}else{
				$allowed_comp_array = array();
				foreach ($companies as $company){
					if(isset($_POST['comp_'.$company[Company::ID]])){
						array_push($allowed_comp_array, $company[Company::ID]);
					}
				}
				$allowed_comp = implode(",", $allowed_comp_array);
			}
			if(isset($_POST['change_agent'])){
				$change_agent = 1;
			}else{
				$change_agent = 0;
			}
		}
		
		if($operation == 'add'){
			$logger->write(ALogger::INFO, __FILE__, "user add operation to [".$username."] by [".$user[User::CODE]."]");
			$password = $username;
			$result = $userService->addUser($name, $username, $password, $role, $desc, $komisyon_rate, $master_agent, $allowed_comp, $change_agent);
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
			$result = $userService->updateUser($user_id, $name, $role, $desc, $komisyon_rate, $master_agent, $allowed_comp, $change_agent);
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
		<h2 class="form-signin-heading">Kullanıcı Bilgileri</h2>
		<form class="form-signin" id="positive_user" action="" method="post" autocomplete="off">
			<label class="login-error" id="user-error"></label>
	        <div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Kod</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="username" name="username">
			</div>
	        <br>
	        <div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Kullanıcı Adı</span>
				<input type="text" class="form-control" aria-describedby="basic-addon1" id="name" name="name">
			</div>
	        <br>
	        <div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Hesap türü</span>
				<select id="select_role" name="select_role" class="form-control" onchange="onChangeUserRole();">
					<option value="0">Rol Seçiniz</option>
					<option value="1">Admin</option>
					<option value="2">Teknik</option>
					<option value="3">Acente</option>
					<option value="4">Finans</option>
				</select>
			</div>
			<br>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">Ek bilgi</span>
				<textarea rows="4" cols="30" class="form-control" aria-describedby="basic-addon1" id="description" name="description"></textarea>
			</div>
			<br>
	        <div id="companies_div" class="input-group" style="visibility: hidden">
				<div class="btn-group">
					<div class="button-group">
			        	<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">&nbsp;Şirket seç<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><input type="checkbox" id="comp_0" name="comp_0">Hepsi</li>
    						<li role="separator" class="divider"></li>
							<?php foreach ($companies as $company){?>
							<?php 	if($company[Company::ACTIVE] == Company::IS_ACTIVE){?>
								<li><input id="comp_<?php echo $company[Company::ID]?>" name="comp_<?php echo $company[Company::ID]?>" type="checkbox"/><?php echo $company[Company::NAME];?></li>
							<?php 	}?>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<br>
	        <div id="change_agent_div" class="input-group" style="visibility: hidden">
				<input type="checkbox" id="change_agent" name="change_agent">
				Acenteleri değiştirebilsin
			</div>
	        <br>
	        <div id="komisyon_div" class="input-group" style="visibility: hidden">
				<span class="input-group-addon" id="basic-addon1">Komisyon oranı %</span>
				<input class="form-control" id="komisyon_rate" name="komisyon_rate">
			</div>
		    <br>
		    <div id="master_agent_div" class="input-group" style="visibility: hidden">
				<span class="input-group-addon" id="basic-addon1">Üst Acente</span>
				<select id="master_agent" name="master_agent" class="form-control">
					<option value="0">Yok</option>
					<?php foreach ($allAgents as $agent){?>
						<?php if($agent[User::ID] == $selected_user[User::ID]){ continue; }?>
						<option value="<?php echo $agent[User::ID]?>"><?php echo $agent[User::NAME]?> - <?php echo $agent[User::CODE]?></option>
					<?php }?>
				</select>
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
<script src="/positive/js/userPage.js"></script>
<script type="text/javascript">
<?php
	if($selected_user[User::ROLE] == User::PERSONEL){
		echo 'document.getElementById("companies_div").style.visibility = "visible";';
		echo 'document.getElementById("change_agent_div").style.visibility = "visible";';
	}
	if($selected_user[User::ROLE] == User::BRANCH){
		echo 'document.getElementById("companies_div").style.visibility = "visible";';
		echo 'document.getElementById("komisyon_div").style.visibility = "visible";';
		echo 'document.getElementById("master_agent_div").style.visibility = "visible";';
	}
	echo '$("#operation").val("'.$operation.'");';
	if(!is_null($selected_user)){
		echo '$("#username").prop("readonly", true);';
		echo 'fillUserForm('.json_encode($selected_user).');';
		if($selected_user[User::ROLE] == User::BRANCH){
			echo "$('#komisyon_rate').val(".$selected_user[User::KOMISYON_RATE].");";
			echo "$('#master_agent').val(".$selected_user[User::MASTER_ID].");";
			
			$allowed_comps = explode(",", $selected_user[User::ALLOWED_COMP]);
			foreach ($allowed_comps as $comp){
				echo "$('#comp_".$comp."').prop('checked', true);";
			}
		}else if($selected_user[User::ROLE] == User::PERSONEL){
			if($selected_user[User::CHANGE_AGENT] == 1){
				echo "$('#change_agent').prop('checked', true);";
			}
			$allowed_comps = explode(",", $selected_user[User::ALLOWED_COMP]);
			foreach ($allowed_comps as $comp){
				echo "$('#comp_".$comp."').prop('checked', true);";
			}
		}
	}
	
	if(!is_null($post_flag)){
		echo 'showPostMessage('.$post_flag.',"'.$post_message.'");';
	}
	if(!empty($_POST)){
		echo "$('#username').val('".$username."');";
		echo "$('#name').val('".$name."');";
		echo "$('#description').val('".$desc."');";
		echo "$('#select_role').val(".$role.");";
		if($selected_user[User::ROLE] == User::BRANCH){
			echo "$('#komisyon_rate').val(".$komisyon_rate.");";
		}
	}
?>
</script>
<script type="text/javascript">
	$('.dropdown-menu input, .dropdown-menu label').click(function(e) {
	    e.stopPropagation();
	});
	$('#admin_1').addClass("active");
</script>
<body>