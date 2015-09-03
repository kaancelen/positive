<?php 

include_once (__DIR__.'/../procedure/CancelProcedures.php');
include_once (__DIR__.'/Service.php');

class CancelService implements Service{
	
	private	$_cancelProcedures;
	
	public function __construct(){
		$this->_cancelProcedures = new CancelProcedures();
	}
	
	public function insert($user_id, $sozlesme, $policy_number, $company_id, $policy_type, $desc){
		return $this->_cancelProcedures->insert($user_id, $sozlesme, $policy_number, $company_id, $policy_type, $desc);
	}
}

?>