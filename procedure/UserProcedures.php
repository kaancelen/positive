<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/../Util/Hash.php');
include_once (__DIR__.'/../classes/user.php');
include_once (__DIR__.'/Procedures.php');

class UserProcedures extends Procedures{

	const TAG = "UserProcedures";

	public function __construct(){
		parent::__construct();
	}
	
	public function changePassword($id, $salt, $hash){
		$sql = "UPDATE USER SET HASH = ?, SALT = ? WHERE ID = ?";
		$this->_db->query($sql, array($hash, $salt, $id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Password couldn't change [".$id."]");
			return false;
		}else{
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Password change succesfully [".$id."]");
			return true;
		}
	}
	
	public function allUsers(){
		$sql = "SELECT * FROM USER ORDER BY ROLE";
		$this->_db->query($sql);
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "no user recorded in DB");
			return null;
		}else{
			$users = array();
			foreach ($result as $object){
				$user = array();
				$user[User::ID] = $object->ID;
				$user[User::NAME] = $object->NAME;
				$user[User::EMAIL] = $object->EMAIL;
				$user[User::CODE] = $object->CODE;
				$user[User::ROLE] = $object->ROLE;
				array_push($users, $user);
			}
			return $users;
		}
	}
	
	public function removeUser($user_id){
		$sql = "DELETE FROM USER_SESSION WHERE ID = ?";
		$this->_db->query($sql, array($user_id));
		
		$sql = "DELETE FROM USER WHERE ID = ?";
		$this->_db->query($sql, array($user_id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			return false;
		}else{
			return true;
		}
	}
	
	public function addUser($name, $email, $username, $password, $role){
		$sql = "INSERT INTO USER(NAME, EMAIL, CODE, ROLE, HASH, SALT) VALUES(?, ?, ?, ?, ?, ?)";
		$salt = Hash::unique();
		$hash = Hash::make($password, $salt);
		$params = array($name, $email, $username, $role, $hash, $salt);
		
		$this->_db->query($sql, $params);
		$result = $this->_db->all();
		
		if(is_null($result)){
			return false;
		}else{
			return true;
		}
	}
}

?>