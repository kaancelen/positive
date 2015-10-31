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
	
	$userService = new UserService();
	$all_users = $userService->allUsers();
?>
<div id="user_table_msg" align="center">
	<div class="alert alert-danger" role="alert" id="user_table_error" style="visibility: hidden;"></div>
	<div class="alert alert-success" role="alert" id="user_table_success" style="visibility: hidden;"></div>
</div>
<script src="/positive/js/usersJs.js"></script>
<script type="text/javascript">
<?php 
	if(Session::exists(Session::FLASH)){
		echo 'showFlash("'.Session::get(Session::FLASH).'");';
		Session::delete(Session::FLASH);
	}
?>
</script>
<div class="container">
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Kodu</b></td>
					<td><b>Adı</b></td>
					<td><b>Rol</b></td>
					<td><b>Alarm</b></td>
					<td>
						<button id="remove_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/admin/user.php?operation=<?php echo urldecode('add');?>'">
						  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;Ekle
						</button>
					</td>
				</tr>
				<tr>
					<td><input type="text" id="username_search" placeholder="	Ara.."></td>
					<td><input type="text" id="name_search" placeholder="	Ara.."></td>
					<td></td>
					<td></td>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($all_users as $user){?>
			<?php 
				$class = "";
				$isActive = true;	  
			?>
			<?php if($user[User::ACTIVE] == User::PASSIVE_USER){
				$class = "row-offer-cancelled";
				$isActive = false;
			}?>
				<tr id="user_<?php echo $user[User::ID]; ?>" class="<?php echo $class; ?>">
					<td><?php echo $user[User::CODE]; ?></td>
					<td><?php echo $user[User::NAME]; ?></td>
					<td><?php 
						switch ($user[User::ROLE]) {
							case 1: echo "Admin"; break;
							case 2: echo "Teknik"; break;
							case 3: echo "Acente"; break;
							case 4: echo "Finans"; break;
						} 
					?></td>
					<td>
						<?php
							if($user[User::ROLE] == User::BRANCH && $user[User::FIRST_LOGIN] == User::FIRST_LOGIN_FLAG){
								?><img src="/positive/images/red-alert.png" alt="Kullanıcının bilgileri eksik"><?php
							}	
						?>
					</td>
					<td>
						<button id="remove_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/profile.php?user_id=<?php echo urldecode($user[User::ID])?>';">
						  <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
						</button>
						<button id="edit_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/admin/user.php?operation=<?php echo urldecode('edit');?>&user_id=<?php echo urldecode($user[User::ID]); ?>'">
						  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						</button>
						<button id="make_user_passive_<?php echo $user[User::ID];?>" type="button" class="btn btn-default btn-sm" aria-label="Left Align" style="visibility: <?php echo $isActive?'':'hidden';?>"
							onclick="toggle_user('<?php echo $user[User::CODE]; ?>', <?php echo $user[User::ID]; ?>, 1)">
						  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
						</button>
						<button id="make_user_active_<?php echo $user[User::ID];?>" type="button" class="btn btn-default btn-sm" aria-label="Left Align" style="visibility: <?php echo $isActive?'hidden':'';?>"
							onclick="toggle_user('<?php echo $user[User::CODE]; ?>', <?php echo $user[User::ID]; ?>, 0)">
						  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						</button>
					</td>
				</tr>
		<?php }?>
			</tbody>
		</table>
	</div>
</div>
<script src="/positive/js/search.js"></script>
<script type="text/javascript">
	$('#admin_1').addClass("active");
</script>
</body>