<?php 

include_once (__DIR__.'/../procedure/GenericProcedures.php');
include_once (__DIR__.'/Service.php');

class GenericService implements Service{
	
	private $_genericProcedures;
	
	public function __construct(){
		$this->_genericProcedures = new GenericProcedures();
	}
	
	public function updateUserEnter($user_id, $request_id, $type){
		$exist = $this->_genericProcedures->isEntryExist($user_id, $request_id);
		if($exist){
			return $this->_genericProcedures->updateUserEnter($user_id, $request_id, $type);
		}else{
			return $this->_genericProcedures->addUserEnter($user_id, $request_id, $type);
		}
	}

	public function getRequestIdsOfNewChatEntries($user_id){
		return $this->_genericProcedures->getRequestIdsOfNewChatEntries($user_id);
	}
}

?>