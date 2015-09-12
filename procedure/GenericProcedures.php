<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');

class GenericProcedures extends Procedures{
	
	const TAG = "GenericProcedures";
	
	public function __construct(){
		parent::__construct();
	}
	
	public function isEntryExist($user_id, $request_id){
		$sql = "SELECT * FROM USER_OFFER_ENTER WHERE USER_ID = ? AND REQUEST_ID = ?";
		$this->_db->query($sql, array($user_id, $request_id));
		if($this->_db->count() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function addUserEnter($user_id, $request_id, $type){
		$sql = "INSERT INTO USER_OFFER_ENTER(USER_ID, REQUEST_ID, TYPE) VALUES(?,?,?)";
		$this->_db->query($sql, array($user_id, $request_id, $type));
		if($this->_db->error()){
			return false;
		}else{
			return true;
		}
	}
	
	public function updateUserEnter($user_id, $request_id, $type){
		$sql = "UPDATE USER_OFFER_ENTER SET TYPE = ?, LAST_ENTER_DATE = NOW() WHERE USER_ID = ? AND REQUEST_ID = ?";
		$this->_db->query($sql, array($type, $user_id, $request_id));
		if($this->_db->error()){
			return false;
		}else{
			return true;
		}
	}

	public function getRequestIdsOfNewChatEntries($user_id){
		$sql = "SELECT DISTINCT uoe.REQUEST_ID FROM USER_OFFER_ENTER uoe, CHAT chat ";
		$sql .= "WHERE uoe.REQUEST_ID = chat.REQUEST_ID AND chat.CREATION_DATE > uoe.LAST_ENTER_DATE ";
		$sql .= "AND uoe.USER_ID = ?";
		$this->_db->query($sql, array($user_id));
		
		$resultObject = $this->_db->all();
		$result = json_decode(json_encode($resultObject));
		
		return $result;
	}
}
?>