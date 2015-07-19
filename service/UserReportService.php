<?php 

include_once (__DIR__.'/../procedure/UserReportProcedures.php');
include_once (__DIR__.'/Service.php');

class UserReportService implements Service{
	
	private $_userReportProcedures;
	
	public function __construct(){
		$this->_userReportProcedures = new UserReportProcedures();
	}
	
	public function createUserReport($user_id, $subject, $content, $file1, $file2){
		return $this->_userReportProcedures->createUserReport($user_id, $subject, $content, $file1, $file2);
	}
	
	public function getAll($status = null){
		return $this->_userReportProcedures->getAll($status);
	}
	
	public function get($id){
		return $this->_userReportProcedures->get($id);
	}
	
	public function remove($id){
		return $this->_userReportProcedures->remove($id);
	}
	
	public function update($id, $status, $feedback){
		return $this->_userReportProcedures->update($id, $status, $feedback);
	}
}

?>