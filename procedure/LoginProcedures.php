<?php

include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/../Util/Hash.php');
include_once (__DIR__.'/../classes/user.php');
include_once (__DIR__.'/Procedures.php');

class LoginProcedures extends Procedures{
	
	const TAG = "LoginProcedures";
	
	public function __construct(){
		parent::__construct();
	}
	
	public function login($username, $password){
		$sql = "SELECT * FROM USER WHERE CODE = ?";
		$this->_db->query($sql, array($username));
		$result = $this->_db->first();
		
		//User not exist
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "User not found : [".$username."]");
			return array(User::ROLE => -1);
		}		
		//Check password
		$hash = Hash::make($password, $result->SALT);
		if($hash == $result->HASH){//matched
			$user = array();
			$user[User::ID] = $result->ID;
			$user[User::ROLE] = $result->ROLE;
			$user[User::NAME] = $result->NAME;
			$user[User::CODE] = $result->CODE;
			$user[User::DESCRIPTION] = $result->DESCRIPTION;
			$user[User::FIRST_LOGIN] = $result->FIRST_LOGIN;
			$user[User::CREATION_DATE] = $result->CREATION_DATE;
			$user[User::KOMISYON_RATE] = $result->KOMISYON_RATE;
			return $user;
		}else{
			//password is wrong
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Password wrong : [".$username."]");
			return array(User::ROLE => 0);
		}
	}
	
	public function remember($id, $hash){
		$sql = "SELECT * FROM USER_SESSION WHERE ID = ?";
		$this->_db->query($sql, array($id));
		$count = $this->_db->count();
		
		if($count == 0){
			$sql = "INSERT INTO USER_SESSION(HASH, ID) VALUES(?, ?)";
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Remember ID not found, new hash will be insert : [".$id."]");
		}else{
			$sql = "UPDATE USER_SESSION SET HASH = ? WHERE ID = ?";
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Remember ID found hash will be updated: [".$id."]");
		}
		$this->_db->query($sql, array($hash, $id));
		$result = $this->_db->all();
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Remember Failed : [".$id."]");
			return false;
		}else{
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Remember Succeed : [".$id."]");
			return true;
		}
	}
	
	public function loginWithHash($hash){
		$sql = "SELECT USER.ID, USER.ROLE, USER.NAME, USER.EMAIL, USER.CODE ";
		$sql .= "FROM USER, USER_SESSION WHERE USER_SESSION.HASH = ? AND USER.ID = USER_SESSION.ID";
		$this->_db->query($sql, array($hash));
		$result = $this->_db->first();
		
		//User not exist
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Login with hash failed [".$hash."]");
			return array(User::ROLE => -1);
		}
		
		$user = array();
		$user[User::ID] = $result->ID;
		$user[User::ROLE] = $result->ROLE;
		$user[User::NAME] = $result->NAME;
		$user[User::CODE] = $result->CODE;
		$user[User::DESCRIPTION] = $result->DESCRIPTION;
		$user[User::FIRST_LOGIN] = $result->FIRST_LOGIN;
		$user[User::CREATION_DATE] = $result->CREATION_DATE;
		$user[User::KOMISYON_RATE] = $result->KOMISYON_RATE;
		$this->_logger->write(ALogger::DEBUG, self::TAG, "Login with hash succeed [".$user[User::CODE]."]");
		
		return $user;
	}
	
	public function removeHash($hash){
		$sql = "DELETE FROM USER_SESSION WHERE HASH = ?";
		
		$this->_db->beginTransaction();
		$this->_db->query($sql, array($hash));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_db->rollback();
			return false;
		}else{
			$this->_db->commit();
			return true;
		}
	}
	
}

?>