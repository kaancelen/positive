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
	
	public function getAllCancelRequests($user_id, $month, $year, $allowed_comp){
		$sql = "SELECT can.*, (SELECT NAME FROM USER WHERE ID = can.USER_ID) BRANCH_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = can.PERSONEL_ID) PERSONEL_NAME, ";
		$sql .= "(SELECT NAME FROM COMPANY WHERE ID = can.COMPANY_ID) COMPANY_NAME FROM CANCEL_REQUEST can ";
		$sql .= "WHERE MONTH(can.CREATION_DATE) = ? AND YEAR(can.CREATION_DATE) = ? ";
		
		$paramArray = array($month, $year);
		
		if(!is_null($user_id)){
			$sql .= " AND (can.USER_ID = ? OR can.PERSONEL_ID = ?) ";
			array_push($paramArray, $user_id);
			array_push($paramArray, $user_id);
		}
		
		if(!is_null($allowed_comp)){
			$companies = explode(',', $allowed_comp);
			$question_marks = array();
			foreach ($companies as $company_id){
				array_push($question_marks, "?");
				array_push($paramArray, $company_id);
			}
			$sql .= " AND can.COMPANY_ID IN (".implode(",", $question_marks).") ";
		}
		
		$sql .= " ORDER BY can.CREATION_DATE DESC";
		
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
			$sql .= " AND can.USER_ID = ? ";
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
	
	public function requestOperation($cancel_id, $user_id, $status){
		$this->_db->beginTransaction();
		
		$sql = "UPDATE CANCEL_REQUEST SET PERSONEL_ID = ?, COMPLETE_DATE = SYSDATE(), STATUS = ? WHERE ID = ?";
		$this->_db->query($sql, array($user_id, $status, $cancel_id));
		if($this->_db->error()){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "cancel requests[".$cancel_id."] couldn't updated");
			$this->_db->rollback();
			return false;
		}else{
			$this->_db->commit();
			return true;
		}
	}
}

?>