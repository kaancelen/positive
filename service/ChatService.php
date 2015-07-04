<?php 

include_once (__DIR__.'/../procedure/ChatProcedures.php');
include_once (__DIR__.'/Service.php');

class ChatService implements Service{
	
	private $_chatProcedures;
	
	public function __construct(){
		$this->_chatProcedures = new ChatProcedures();
	}
	
	public function addEntry($request_id, $user_id, $user_name, $text){
		return $this->_chatProcedures->addEntry($request_id, $user_id, $user_name, $text);
	}
	
	public function getEntries($request_id){
		return $this->_chatProcedures->getEntries($request_id);
	}
	
}

?>