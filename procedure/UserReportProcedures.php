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
	
	public function createUserReport($subject, $content, $file1, $file2){
		$this->_db->beginTransaction();
		$sql = "INSERT INTO USER_EXCEPTION(STATUS, SUBJECT, CONTENT) VALUES(?,?,?)";
		$this->_db->query($sql, array(0, $subject, $content));
		
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
}

?>