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
	$agent_relation = null;
	$post_message = null;
	$post_flag = null;
	$userService = new UserService();
	$agentService = new AgentService();
	if(isset($_GET['operation'])){
		$operation = Util::cleanInput(urlencode($_GET['operation']));
	}
	if(isset($_GET['user_id'])){
		$user_id = Util::cleanInput(urlencode($_GET['user_id']));
		$selected_user = $userService->getUser($user_id);
		$agent_relation = $agentService->getAgentRelation($user_id);
	}
	
	$allAgents = $userService->allTypeOfUsers(User::BRANCH);
	
	$companyService = new CompanyService();
	$companies = $companyService->getAll();
	
	if(!empty($_POST)){
		$username = Util::cleanInput($_POST['username']);
		$name = Util::cleanInput($_POST['name']);
		$role = Util::cleanInput($_POST['select_role']);
		$desc = str_replace(array("\n","\r"), ' ', Util::cleanInput($_POST['description']));
		$operation = Util::cleanInput($_POST['operation']);
		
		$allowed_comp = "0";
		$change_agent = 0;
		
		if($role == User::BRANCH){
			$komisyon = Util::cleanInput($_POST['komisyon']);
			$ust_acente = Util::cleanInput($_POST['ust_acente']);
			$ust_komisyon = Util::cleanInput($_POST['ust_komisyon']);
			$bagli_acente = Util::cleanInput($_POST['bagli_acente']);
			$bagli_komisyon = Util::cleanInput($_POST['bagli_komisyon']);
			
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
			$result = $userService->addUser($name, $username, $password, $role, $desc, $allowed_comp, $change_agent);
			if($result == null){
				$post_flag = 0;
				$post_message = "Kullanıcı ekleme işlemi başarısız, kullanıcı adı mevcut!";
			}else if($result == 0){
				$post_flag = 0;
				$post_message = "Kullanıcı ekleme işlemi başarısız";
			}else{
				if($role == User::BRANCH){
					$agentService->upsertRelation($result, $komisyon, $ust_acente, $ust_komisyon, $bagli_acente, $bagli_komisyon);
				}
				Session::flash(Session::FLASH, $username." kullanıcısı başarı ile eklendi.");
				Util::redirect("/positive/admin/users.php");
			}
		}else if($operation == 'edit'){
			$logger->write(ALogger::INFO, __FILE__, "user edit to operation to [".$selected_user[User::CODE]."] by [".$user[User::CODE]."]");
			$result = $userService->updateUser($user_id, $name, $role, $desc, $allowed_comp, $change_agent);
			if($result == null){
				$post_flag = 0;
				$post_message = "Kullanıcı düzenleme işlemi başarısız, kullanıcı adı mevcut!";
			}else if(!$result){
				$post_flag = 0;
				$post_message = "Kullanıcı [".$username."] düzenleme işlemi başarısız";
			}else{
				if($role == User::BRANCH){
					$agentService->upsertRelation($user_id, $komisyon, $ust_acente, $ust_komisyon, $bagli_acente, $bagli_komisyon);
				}
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
	        <div id="agent_div" style="visibility: hidden">
		        <div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Komisyon %</span>
					<input class="form-control" id="komisyon" name="komisyon" value="0">
				</div>
			    <div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Üst Acente</span>
					<select id="ust_acente" name="ust_acente" class="form-control">
						<option value="0">Yok</option>
						<?php foreach ($allAgents as $agent){?>
							<?php if($agent[User::ID] == $selected_user[User::ID]){ continue; }?>
							<option value="<?php echo $agent[User::ID]?>"><?php echo $agent[User::NAME]?> - <?php echo $agent[User::CODE]?></option>
						<?php }?>
					</select>
				</div>
		        <div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Üst Acente Komisyon %</span>
					<input class="form-control" id="ust_komisyon" name="ust_komisyon" value="0">
				</div>
			    <div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Bağlı Acente</span>
					<select id="bagli_acente" name="bagli_acente" class="form-control">
						<option value="0">Yok</option>
						<?php foreach ($allAgents as $agent){?>
							<?php if($agent[User::ID] == $selected_user[User::ID]){ continue; }?>
							<option value="<?php echo $agent[User::ID]?>"><?php echo $agent[User::NAME]?> - <?php echo $agent[User::CODE]?></option>
						<?php }?>
					</select>
				</div>
		        <div class="input-group">
					<span class="input-group-addon" id="basic-addon1">Bağlı Komisyon %</span>
					<input class="form-control" id="bagli_komisyon" name="bagli_komisyon" value="0">
				</div>
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
		echo 'document.getElementById("agent_div").style.visibility = "visible";';
	}
	echo '$("#operation").val("'.$operation.'");';
	if(!is_null($selected_user)){
		echo '$("#username").prop("readonly", true);';
		echo 'fillUserForm('.json_encode($selected_user).');';
		if($selected_user[User::ROLE] == User::BRANCH){
			echo "$('#komisyon').val(".$agent_relation[AgentRelation::KOMISYON].");";
			echo "$('#ust_acente').val(".$agent_relation[AgentRelation::UST_ACENTE].");";
			echo "$('#ust_komisyon').val(".$agent_relation[AgentRelation::UST_KOMISYON].");";
			echo "$('#bagli_acente').val(".$agent_relation[AgentRelation::BAGLI_ACENTE].");";
			echo "$('#bagli_komisyon').val(".$agent_relation[AgentRelation::BAGLI_KOMISYON].");";
			
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
			echo "$('#komisyon').val(".$komisyon.");";
			echo "$('#ust_acente').val(".$ust_acente.");";
			echo "$('#ust_komisyon').val(".$ust_komisyon.");";
			echo "$('#bagli_acente').val(".$bagli_acente.");";
			echo "$('#bagli_komisyon').val(".$bagli_komisyon.");";
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