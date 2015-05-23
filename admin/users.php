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

<div class="container">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b>Kullanıcı Adı</b></td>
					<td><b>İsim</b></td>
					<td><b>E-Posta</b></td>
					<td><b>Rol</b></td>
					<td><b>Düzenle</b></td>
					<td><b>Sil</b></td>
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
							onclick="edit_user(<?php echo $user[User::ID]; ?>)">
						  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						</button>
					</td>
					<td>
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
<script src="../js/userOperations.js"></script>

</body>