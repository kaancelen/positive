<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 

include_once (__DIR__.'/Util/Hash.php');
include_once (__DIR__.'/service/UserService.php');

// $salt = Hash::unique();
// $hash = Hash::make("admin", $salt);

// echo $salt.'<br>'.$hash.'<br>';
$userService = new UserService();
// $result = $userService->changePassword(12, $salt, $hash);
// echo $result;

$userService->addUser("Kaan Çelen", "bkaancelen@gmail.com", "kaan", "celen", 2);
//TODO control username before insert!!

?>