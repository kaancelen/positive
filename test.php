<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	include_once (__DIR__.'/db/db.php');
	include_once (__DIR__.'/Util/util.php');
	include_once (__DIR__.'/Util/Hash.php');
	
// 	$password = "personel";
// 	$salt = Hash::unique();
//  	$hash = Hash::make($password, $salt);
	
//  	$db = DB::getInstance();
	
// 	$sql = "INSERT INTO USER(NAME, EMAIL, CODE, HASH, SALT, ROLE) VALUES(?,?,?,?,?,?)";
// 	$params = array(
// 			'Branch',
// 			'branch@hero.com',
// 			'personel',
// 			$hash,
// 			$salt,
// 			3
// 	);
// 	$db->query($sql, $params);

// 	$code = 'branch';
// 	$password = 'branch1';

// 	$sql = "SELECT * FROM USER WHERE CODE = ?";
// 	$params = array($code);
	
// 	$db->query($sql, $params);
// 	$result = $db->first();
	
// 	$hash = Hash::make($password, $result->SALT);
	
// 	echo $hash.'<br>'.$result->HASH.'<br>';
	
// 	if($hash == $result->HASH){
// 		echo "LOGIN";
// 	}else{
// 		echo "ERROR";
// 	}
	
?>