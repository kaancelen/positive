<?php 

include_once (__DIR__.'/../procedure/LoginProcedures.php');
include_once (__DIR__.'/Service.php');

class LoginService implements Service{
	
	private $_loginProcedures;
	
	public function __construct(){
		$this->_loginProcedures = new LoginProcedures();
	}
	
	public function login($username, $password){
		return $this->_loginProcedures->login($username, $password);
	}
	
	public function remember($id, $hash){
		return $this->_loginProcedures->remember($id, $hash);
	}
	
	public function loginWithHash($hash){
		return $this->_loginProcedures->loginWithHash($hash);
	}
	
	public function removeHash($hash){
		return $this->_loginProcedures->removeHash($hash);
	}
}

?>