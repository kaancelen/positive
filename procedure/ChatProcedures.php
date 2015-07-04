<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/chat.php');

class ChatProcedures extends Procedures{
	
	const TAG = "ChatProcedures";
	
	public function __construct(){
		parent::__construct();
	}
	
	public function addEntry($request_id, $user_id, $user_name, $text){
		$sql = "INSERT INTO CHAT(REQUEST_ID, USER_ID, USER_NAME, TEXT) VALUES(?,?,?,?)";
		$this->_db->query($sql, array($request_id, $user_id, $user_name, $text));
		$error = $this->_db->error();
		return !$error;
	}
	
	public function getEntries($request_id){
		$sql = "SELECT * FROM CHAT WHERE REQUEST_ID = ? ORDER BY ID";
		$this->_db->query($sql, array($request_id));
		$result = $this->_db->all();
		
		$allChat = array();
		if(!empty($result)){
			foreach ($result as $object){
				$chat = json_decode(json_encode($object), true);
				array_push($allChat, $chat);
			}
		}
		
		return $allChat;
	}
	
}

?>