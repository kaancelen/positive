<?php 

include_once (__DIR__.'/../procedure/LoginProcedures.php');

class LoginService{
	
	private $_loginProcedures;
	
	public function __construct(){
		$this->_loginProcedures = new LoginProcedures();
	}
	
	public function login($username, $password, $remember){
		return $this->_loginProcedures->login($username, $password, $remember);
	}
	
}

?>