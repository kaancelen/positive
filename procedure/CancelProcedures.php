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
	
	public function getAllCancelRequests($user_id, $time){
		$paramArray = array();
		
		$sql = "SELECT can.*, (SELECT NAME FROM USER WHERE ID = can.USER_ID) BRANCH_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = can.PERSONEL_ID) PERSONEL_NAME, ";
		$sql .= "(SELECT NAME FROM COMPANY WHERE ID = can.COMPANY_ID) COMPANY_NAME FROM CANCEL_REQUEST can WHERE 1=1 ";
		
		if(!is_null($user_id)){
			$sql .= "AND (can.USER_ID = ? OR can.PERSONEL_ID = ?) ";
			array_push($paramArray, $user_id);
			array_push($paramArray, $user_id);
		}
		if(!is_null($time)){
			$sql .= "AND can.CREATION_DATE > ? ";
			array_push($paramArray, $time);
		}
		
		$sql .= "ORDER BY can.CREATION_DATE DESC";
		
		$this->_db->query($sql, $paramArray);
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "cancel requests[".$user_id."] not found in DB");
			return null;
		}else{
			$allCancelRequests = array();
			foreach ($result as $object){
				$cancelRequest = json_decode(json_encode($object), true);
				array_push($allCancelRequests, $cancelRequest);
			}
				
			return $allCancelRequests;
		}
	}
	
	public function getCancelRequest($cancel_id, $user_id){
		$paramArray = array($cancel_id);
		
		$sql = "SELECT can.*, (SELECT NAME FROM USER WHERE ID = can.USER_ID) BRANCH_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = can.PERSONEL_ID) PERSONEL_NAME, ";
		$sql .= "(SELECT NAME FROM COMPANY WHERE ID = can.COMPANY_ID) COMPANY_NAME FROM CANCEL_REQUEST can ";
		$sql .= "WHERE can.ID = ?";
		
		if(!is_null($user_id)){
			$sql .= "AND can.USER_ID = ? ";
			array_push($paramArray, $user_id);
		}
		
		$this->_db->query($sql, $paramArray);
		$result = $this->_db->first();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "cancel requests[".$cancel_id."] not found in DB");
			return null;
		}else{
			$cancelRequest = json_decode(json_encode($result), true);
			return $cancelRequest;
		}
	}
	
}

?>