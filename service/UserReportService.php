<?php 

include_once (__DIR__.'/../procedure/UserReportProcedures.php');
include_once (__DIR__.'/Service.php');

class UserReportService implements Service{
	
	private $_userReportProcedures;
	
	public function __construct(){
		$this->_userReportProcedures = new UserReportProcedures();
	}
	
	public function createUserReport($subject, $content, $file1, $file2){
		return $this->_userReportProcedures->createUserReport($subject, $content, $file1, $file2);
	}
}

?>