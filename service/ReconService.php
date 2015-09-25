<?php 

include_once (__DIR__.'/../procedure/ReconProcedures.php');
include_once (__DIR__.'/Service.php');
include_once (__DIR__.'/../classes/ReconPolicy.php');

class ReconService implements Service{
	
	private $_reconProcedures;
	
	public function __construct(){
		$this->_reconProcedures = new ReconProcedures();
	}

	public function reconDifferent($month, $year, $user_id, $user_role){
		return $this->_reconProcedures->reconDifferent($month, $year, $user_id, $user_role);
	}
	
	public function getPoliciesInMonth($month, $year, $user_id, $user_role) {
		return $this->_reconProcedures->getPoliciesInMonth($month, $year, $user_id, $user_role);
	}

	public function insertRecon($reconPolicy){
		if($this->_reconProcedures->reconExist($reconPolicy[ReconPolicy::POLICY_ID])){
			return false;
		}
		return $this->_reconProcedures->insertRecon($reconPolicy);
	}

	public function getRecons($month, $year, $user_id, $user_role){
		return $this->_reconProcedures->getRecons($month, $year, $user_id, $user_role);
	}

	public function getRecon($takip_no, $user_id, $user_role){
		return $this->_reconProcedures->getRecon($takip_no, $user_id, $user_role);
	}

	public function updateRecon($takip_no, $recon_update_params){
		return $this->_reconProcedures->updateRecon($takip_no, $recon_update_params);
	}
}

?>