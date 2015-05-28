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
			return false;
		}else{
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
				$user[User::CODE] = $object->CODE;
				$user[User::ROLE] = $object->ROLE;
				$user[User::DESCRIPTION] = $object->DESCRIPTION;
				$user[User::FIRST_LOGIN] = $object->FIRST_LOGIN;
				$user[User::CREATION_DATE] = $object->CREATION_DATE;
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
			$this->_logger->write(ALogger::DEBUG, self::TAG, "user removed, id=[".$user_id."]");
			return true;
		}
	}
	
	public function addUser($name, $username, $password, $role, $desc){
		$sql = "INSERT INTO USER(NAME, CODE, ROLE, HASH, SALT, DESCRIPTION) VALUES(?, ?, ?, ?, ?, ?)";
		$salt = Hash::unique();
		$hash = Hash::make($password, $salt);
		$params = array($name, $username, $role, $hash, $salt, $desc);
		
		$this->_db->query($sql, $params);
		$result = $this->_db->all();
		
		if(is_null($result)){
			return false;
		}else{
			return true;
		}
	}
	
	public function updateUser($user_id, $name, $role, $desc){
		$sql = "UPDATE USER SET NAME = ?, ROLE = ?, DESCRIPTION = ? WHERE ID = ?";
		$this->_db->query($sql, array($name, $role, $desc, $user_id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			return false;
		}else{
			return true;
		}
	}
	
	public function exist($username){
		$sql = "SELECT CODE FROM USER WHERE CODE = ?";
		$this->_db->query($sql, array($username));
		
		$result = $this->_db->count();
		
		if($result > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function getUser($user_id){
		$sql = "SELECT * FROM USER WHERE ID = ?";
		$this->_db->query($sql, array($user_id));
		
		$result = $this->_db->first();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "user[".$user_id."] not found in DB");
			return null;
		}else{
			$user = array();
			$user[User::ID] = $user_id;
			$user[User::CODE] = $result->CODE;
			$user[User::NAME] = $result->NAME;
			$user[User::ROLE] = $result->ROLE;
			$user[User::DESCRIPTION] = $result->DESCRIPTION;
			$user[User::FIRST_LOGIN] = $result->FIRST_LOGIN;
			$user[User::CREATION_DATE] = $result->CREATION_DATE;
			return $user;
		}
	}
}

?>