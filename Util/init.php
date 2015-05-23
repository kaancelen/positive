<?php
session_start();

include_once (__DIR__.'/util.php');
include_once (__DIR__.'/session.php');
include_once (__DIR__.'/cookie.php');
include_once (__DIR__.'/Hash.php');
include_once (__DIR__.'/../classes/user.php');
include_once (__DIR__.'/../service/LoginService.php');

$loggedIn = false;
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