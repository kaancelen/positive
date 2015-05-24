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
<div class="container">
	<div id="user_table" class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Kullanıcı Adı</b></td>
					<td><b>İsim</b></td>
					<td><b>E-Posta</b></td>
					<td><b>Rol</b></td>
					<td>
						<button id="remove_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/admin/user.php?operation=<?php echo urldecode('add');?>'">
						  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;Ekle
						</button>
					</td>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($all_users as $user){?>
				<tr id="user_<?php echo $user[User::ID]; ?>">
					<td><?php echo $user[User::CODE]; ?></td>
					<td><?php echo $user[User::NAME]; ?></td>
					<td><?php echo $user[User::EMAIL]; ?></td>
					<td><?php 
						switch ($user[User::ROLE]) {
							case 1: echo "Admin"; break;
							case 2: echo "Personel"; break;
							case 3: echo "Acente"; break;
						} 
					?></td>
					<td>
						<button id="edit_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="location.href = '/positive/admin/user.php?operation=<?php echo urldecode('edit');?>&user_id=<?php echo urldecode($user[User::ID]); ?>'">
						  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						</button>
						<button id="remove_user" type="button" class="btn btn-default btn-sm" aria-label="Left Align"
							onclick="remove_user('<?php echo $user[User::CODE]; ?>', <?php echo $user[User::ID]; ?>)">
						  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
						</button>
					</td>
				</tr>
		<?php }?>
			</tbody>
		</table>
	</div>
</div>
<script src="../js/removeUser.js"></script>

</body>