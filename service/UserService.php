<?php

include_once (__DIR__.'/../procedure/UserProcedures.php');
include_once (__DIR__.'/Service.php');

class UserService implements Service{
	
	private $_userProcedures;
	
	public function __construct(){
		$this->_userProcedures = new UserProcedures();
	}
	
	public function changePassword($id, $salt, $hash){
		return $this->_userProcedures->changePassword($id, $salt, $hash);
	}
	
	public function allUsers(){
		return $this->_userProcedures->allUsers();
	}
	
	public function removeUser($user_id){
		return $this->_userProcedures->removeUser($user_id);
	}
	
	public function addUser($name, $email, $username, $password, $role){
		return $this->_userProcedures->addUser($name, $email, $username, $password, $role);
	}
	
}
?>