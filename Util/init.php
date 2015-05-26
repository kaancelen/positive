<?php
session_start();

include_once (__DIR__.'/util.php');
include_once (__DIR__.'/session.php');
include_once (__DIR__.'/cookie.php');
include_once (__DIR__.'/Hash.php');
include_once (__DIR__.'/../classes/user.php');
include_once (__DIR__.'/../service/LoginService.php');
include_once (__DIR__.'/../service/UserService.php');
include_once (__DIR__.'/../service/CompanyService.php');
include_once (__DIR__.'/../Logger/ALogger.php');

$loggedIn = false;
$logger = ALogger::getInstance();
if(Session::exists(Session::USER)){
	$loggedIn = true;
}else if(Cookie::exists(Cookie::HASH)){
	$loginService = new LoginService();
	$user = $loginService->loginWithHash(Cookie::get(Cookie::HASH));
	if($user[User::ROLE] > 0){
		Session::put(Session::USER, $user);
		$loggedIn = true;
	}
}
?>