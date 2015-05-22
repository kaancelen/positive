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
	
	public function login($username, $password, $remember){
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
	
}

?>