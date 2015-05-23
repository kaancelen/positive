<?php

include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/../Util/Hash.php');
include_once (__DIR__.'/../classes/user.php');

class LoginProcedures{
	
	private $_db;
	private $_logger;
	
	const TAG = "LoginProcedures";
	
	public function __construct(){
		$this->_logger = ALogger::getInstance();
		$this->_db = DB::getInstance();
	}
	
	public function login($username, $password){
		$sql = "SELECT * FROM USER WHERE CODE = ?";
		$this->_db->query($sql, array($username));
		$result = $this->_db->first();
		
		//User not exist
		if(is_null($result)){
			return array(User::ROLE => -1);
		}		
		//Check password
		$hash = Hash::make($password, $result->SALT);
		if($hash == $result->HASH){//Not matched
			$user = array();
			$user[User::ID] = $result->ID;
			$user[User::ROLE] = $result->ROLE;
			$user[User::NAME] = $result->NAME;
			$user[User::EMAIL] = $result->EMAIL;
			$user[User::CODE] = $result->CODE;
			return $user;
		}else{
			//password is wrong
			return array(User::ROLE => 0);
		}
	}
	
	public function remember($id, $hash){
		$sql = "SELECT * FROM USER_SESSION WHERE ID = ?";
		$this->_db->query($sql, array($id));
		$count = $this->_db->count();
		if($count == 0){
			$sql = "INSERT INTO USER_SESSION(HASH, ID) VALUES(?, ?)";
		}else{
			$sql = "UPDATE USER_SESSION SET HASH = ? WHERE ID = ?";
		}
		$this->_db->query($sql, array($hash, $id));
		return true;
	}
	
	public function loginWithHash($hash){
		$sql = "SELECT USER.ID, USER.ROLE, USER.NAME, USER.EMAIL, USER.CODE ";
		$sql .= "FROM USER, USER_SESSION WHERE USER_SESSION.HASH = ? AND USER.ID = USER_SESSION.ID";
		$this->_db->query($sql, array($hash));
		$result = $this->_db->first();
		
		//User not exist
		if(is_null($result)){
			return array(User::ROLE => -1);
		}
		
		$user = array();
		$user[User::ID] = $result->ID;
		$user[User::ROLE] = $result->ROLE;
		$user[User::NAME] = $result->NAME;
		$user[User::EMAIL] = $result->EMAIL;
		$user[User::CODE] = $result->CODE;
		return $user;
	}
	
	public function removeHash($hash){
		$sql = "DELETE FROM USER_SESSION WHERE HASH = ?";
		$this->_db->query($sql, array($hash));
		$result = $this->_db->all();
		
		if(is_null($result)){
			return false;
		}else{
			return true;
		}
	}
	
}

?>