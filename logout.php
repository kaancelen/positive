<?php 
	include_once (__DIR__.'/Util/init.php');
	$user = Session::get(Session::USER);
	$logger->write(ALogger::INFO, __FILE__, "Logout request come [".$user[User::CODE]."]");
	if(Cookie::exists(Cookie::HASH)){
		$loginService = new LoginService();
		$loginService->removeHash(Cookie::get(Cookie::HASH));//remove from db
		Cookie::delete(Cookie::HASH);	//remove from cookie
	}
	Session::delete(Session::USER);//remove session
	Util::redirect("/positive");
?>