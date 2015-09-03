<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/cancelRequest.php');

class CancelProcedures extends Procedures{

	const TAG = "CancelProcedures";

	public function __construct(){
		parent::__construct();
	}
	
	public function insert($user_id, $sozlesme, $policy_number, $company_id, $policy_type, $desc){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO CANCEL_REQUEST(USER_ID, SOZLESME, POLICY_NUMBER, COMPANY_ID, POLICY_TYPE, EK_BILGI) ";
		$sql .= "VALUES(?,?,?,?,?,?)";
		$this->_db->query($sql, array($user_id, $sozlesme, $policy_number, $company_id, $policy_type, $desc));
		if($this->_db->error()){
			$this->_db->rollback();
			return null;
		}
		$request_id = (int)$this->_db->lastInsertId();
		$this->_db->commit();
		
		return $request_id;
	}
	
}

?>