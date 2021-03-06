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
	
	public function allTypeOfUsers($type){
		return $this->_userProcedures->allTypeOfUsers($type);
	}
	
	public function removeUser($user_id){
		return $this->_userProcedures->removeUser($user_id);
	}
	
	public function addUser($name, $username, $password, $role, $desc, $allowed_comp, $change_agent){
		if($this->_userProcedures->exist($username)){
			return null;
		}
		return $this->_userProcedures->addUser($name, $username, $password, $role, $desc, $allowed_comp, $change_agent);
	}
	
	public function updateUser($user_id, $name, $role, $desc, $allowed_comp, $change_agent){
		return $this->_userProcedures->updateUser($user_id, $name, $role, $desc, $allowed_comp, $change_agent);
	}
	
	public function getUser($user_id){
		return $this->_userProcedures->getUser($user_id);
	}
	
	public function toggleUserActivity($user_id, $makeActive){
		return $this->_userProcedures->toggleUserActivity($user_id, $makeActive);
	}
}
?>