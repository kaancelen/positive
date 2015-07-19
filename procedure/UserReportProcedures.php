<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/userReport.php');

class UserReportProcedures extends Procedures{

	const TAG = "UserReportProcedures";

	public function __construct(){
		parent::__construct();
	}
	
	public function createUserReport($user_id, $subject, $content, $file1, $file2){
		$this->_db->beginTransaction();
		$sql = "INSERT INTO USER_EXCEPTION(USER_ID, STATUS, SUBJECT, CONTENT) VALUES(?,?,?,?)";
		$this->_db->query($sql, array($user_id, 0, $subject, $content));
		
		if($this->_db->error()){
			$this->_logger->write(ALogger::ERROR, self::TAG, "User report couldn't created!");
			$this->_db->rollback();
			return -1;
		}else{
			$report_id = $this->_db->lastInsertId();
			if(!is_null($file1)){
				$sql = "UPDATE USER_EXCEPTION SET FILE1 = ? WHERE ID = ?";
				$this->_db->query($sql, array($file1, $report_id));
				if($this->_db->error()){
					$this->_logger->write(ALogger::ERROR, self::TAG, "file1 couldn't updated!");
					$this->_db->rollback();
					return -1;
				}
			}
			if(!is_null($file2)){
				$sql = "UPDATE USER_EXCEPTION SET FILE2 = ? WHERE ID = ?";
				$this->_db->query($sql, array($file2, $report_id));
				if($this->_db->error()){
					$this->_logger->write(ALogger::ERROR, self::TAG, "file2 couldn't updated!");
					$this->_db->rollback();
					return -1;
				}
			}
			$this->_db->commit();
			return $report_id;
		}
	}
	
	public function getAll($status){
		$params = array();
		$sql = "SELECT ue.ID, ue.STATUS, ue.SUBJECT, ue.CONTENT, ue.FILE1, ue.FILE2, ue.FEEDBACK, ";
		$sql .= "ue.USER_ID, ue.CREATION_DATE, us.NAME USER_NAME FROM USER_EXCEPTION ue,USER us WHERE ue.USER_ID = us.ID";
		if(!is_null($status)){
			$sql .= " AND STATUS = ?";
			array_push($params, $status);
		}
		$this->_db->query($sql, $params);
		$result = $this->_db->all();
		if(is_null($result)){
			return null;
		}else{
			$response = array();
			foreach ($result as $object){
				$report = array();
				$report[UserReport::ID] = $object->ID;
				$report[UserReport::STATUS] = $object->STATUS;
				$report[UserReport::SUBJECT] = $object->SUBJECT;
				$report[UserReport::CONTENT] = $object->CONTENT;
				$report[UserReport::FILE1] = $object->FILE1;
				$report[UserReport::FILE2] = $object->FILE2;
				$report[UserReport::FEEDBACK] = $object->FEEDBACK;
				$report[UserReport::USER_ID] = $object->USER_ID;
				$report[UserReport::CREATION_DATE] = $object->CREATION_DATE;
				$report[UserReport::USER_NAME] = $object->USER_NAME;
				array_push($response, $report);
			}
			return $response;
		}
	}
	
	public function get($id){
		$sql = "SELECT ue.ID, ue.STATUS, ue.SUBJECT, ue.CONTENT, ue.FILE1, ue.FILE2, ue.FEEDBACK, ";
		$sql .= "ue.USER_ID, ue.CREATION_DATE, us.NAME USER_NAME FROM USER_EXCEPTION ue,USER us ";
		$sql .= "WHERE ue.USER_ID = us.ID AND ue.ID = ?";
		$this->_db->query($sql, array($id));
		$result = $this->_db->first();
		if(is_null($result)){
			return null;
		}else{
			$report = array();
			$report[UserReport::ID] = $result->ID;
			$report[UserReport::STATUS] = $result->STATUS;
			$report[UserReport::SUBJECT] = $result->SUBJECT;
			$report[UserReport::CONTENT] = $result->CONTENT;
			$report[UserReport::FILE1] = $result->FILE1;
			$report[UserReport::FILE2] = $result->FILE2;
			$report[UserReport::FEEDBACK] = $result->FEEDBACK;
			$report[UserReport::USER_ID] = $result->USER_ID;
			$report[UserReport::CREATION_DATE] = $result->CREATION_DATE;
			$report[UserReport::USER_NAME] = $result->USER_NAME;
			return $report;
		}
	}
	
	public function remove($id){
		$report = $this->get($id);
		if(is_null($report)){
			return false;
		}else{
			//first remove files
			if(!empty($report[UserReport::FILE1])){
				unlink($report[UserReport::FILE1]);
			}
			if(!empty($report[UserReport::FILE2])){
				unlink($report[UserReport::FILE2]);
			}
			
			$sql = "DELETE FROM USER_EXCEPTION WHERE ID = ?";
			$this->_db->query($sql, array($id));
			if($this->_db->error()){
				return false;
			}else{
				return true;
			}
		}
	}
	
	public function update($id, $status, $feedback){
		$sql = "UPDATE USER_EXCEPTION SET STATUS = ?, FEEDBACK = ? WHERE ID = ?";
		$this->_db->query($sql, array($status, $feedback, $id));
		if($this->_db->error()){
			return false;
		}else{
			return true;
		}
	}
}

?>