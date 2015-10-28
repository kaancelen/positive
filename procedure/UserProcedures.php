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
		
		$this->_db->beginTransaction();
		$this->_db->query($sql, array($hash, $salt, $id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_db->rollback();
			return false;
		}else{
			$this->_db->commit();
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
				$user = json_decode(json_encode($object), true);
				array_push($users, $user);
			}
			return $users;
		}
	}
	
	public function allTypeOfUsers($type){
		$sql = "SELECT * FROM USER WHERE ROLE = ? ORDER BY NAME";
		$this->_db->query($sql, array($type));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "no user recorded in DB");
			return null;
		}else{
			$users = array();
			foreach ($result as $object){
				$user = json_decode(json_encode($object), true);
				array_push($users, $user);
			}
			return $users;
		}
	}
	
	public function removeUser($user_id){
		$this->_db->beginTransaction();
		
		$sql = "DELETE FROM AGENT_DETAIL WHERE USER_ID = ?";
		$this->_db->query($sql, array($user_id));
		
		$sql = "DELETE FROM AGENT_RELATION WHERE ACENTE = ?";
		$this->_db->query($sql, array($user_id));
		
		$sql = "DELETE FROM USER_SESSION WHERE ID = ?";
		$this->_db->query($sql, array($user_id));
		
		$sql = "DELETE FROM USER_OFFER_ENTER WHERE USER_ID = ?";
		$this->_db->query($sql, array($user_id));
		
		$sql = "DELETE FROM USER WHERE ID = ?";
		$this->_db->query($sql, array($user_id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_db->rollback();
			return false;
		}else{
			$this->_db->commit();
			$this->_logger->write(ALogger::DEBUG, self::TAG, "user removed, id=[".$user_id."]");
			return true;
		}
	}
	
	public function addUser($name, $username, $password, $role, $desc, $allowed_comp, $change_agent){
		$sql = "INSERT INTO USER(NAME, CODE, ROLE, HASH, SALT, DESCRIPTION, ALLOWED_COMP, CHANGE_AGENT) VALUES(?,?,?,?,?,?,?,?)";
		$salt = Hash::unique();
		$hash = Hash::make($password, $salt);
		$params = array($name, $username, $role, $hash, $salt, $desc, $allowed_comp, $change_agent);
		
		$this->_db->beginTransaction();
		$this->_db->query($sql, $params);
		$user_id = $this->_db->lastInsertId();
		
		if($this->_db->error()){
			$this->_db->rollback();
			return 0;
		}else{
			$this->_db->commit();
			return $user_id;
		}
	}
	
	public function updateUser($user_id, $name, $role, $desc, $allowed_comp, $change_agent){
		$sql = "UPDATE USER SET NAME = ?, ROLE = ?, DESCRIPTION = ?, ALLOWED_COMP = ?, CHANGE_AGENT = ? WHERE ID = ?";
		
		$this->_db->beginTransaction();
		$this->_db->query($sql, array($name, $role, $desc, $allowed_comp, $change_agent, $user_id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_db->rollback();
			return false;
		}else{
			$this->_db->commit();
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
			$user = json_decode(json_encode($result), true);
			return $user;
		}
	}
}

?>