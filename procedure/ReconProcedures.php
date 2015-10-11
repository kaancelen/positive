<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/user.php');
include_once (__DIR__.'/../classes/ReconPolicy.php');

class ReconProcedures extends Procedures{
	
	const TAG = "ReconProcedures";
	
	public function __construct(){
		parent::__construct();
	}

	public function reconDifferent($month, $year, $user_id, $user_role){
		$params = array();
		//Policy Part
		$policy_sql = "SELECT COUNT(*) FROM OFFER_REQUEST ofr, OFFER_REQUEST_COMPANY orc, OFFER_RESPONSE ofre, ";
		$policy_sql .= "COMPANY co,POLICY po WHERE ofr.ID = orc.REQUEST_ID AND ofre.ID = orc.OFFER_ID AND co.ID = orc.COMPANY_ID ";
		$policy_sql .= "AND po.ID = orc.POLICY_ID AND MONTH(po.CREATION_DATE) = ? AND YEAR(po.CREATION_DATE) = ?";
		array_push($params, $month);
		array_push($params, $year);
		if($user_role == User::BRANCH){
			$policy_sql .= " AND ofr.USER_ID = ?";
			array_push($params, $user_id);
		}
		//canceled policy part
		$policy_cancel_sql = "SELECT COUNT(*) FROM CANCEL_REQUEST can WHERE can.STATUS = 1 AND MONTH(can.CREATION_DATE) = ?";
		$policy_cancel_sql .= " AND YEAR(can.CREATION_DATE) = ?";
		array_push($params, $month);
		array_push($params, $year);
		if($user_role == User::BRANCH){
			$policy_sql .= " AND can.USER_ID = ?";
			array_push($params, $user_id);
		}
		//Recon part
		$recon_sql = "SELECT COUNT(*) FROM RECON WHERE MONTH(TANZIM_TARIHI) = ? AND YEAR(TANZIM_TARIHI) = ?";
		array_push($params, $month);
		array_push($params, $year);
		if($user_role == User::BRANCH){
			$recon_sql .= " AND PRODUKTOR_ID = ?";
			array_push($params, $user_id);
		}
		if($user_role == User::PERSONEL){
			$recon_sql .= " AND (TEKNIKCI_ID = ? OR TEKNIKCI_ID_POLICY = ?)";
			array_push($params, $user_id);
			array_push($params, $user_id);
		}
		
		$sql = "SELECT (".$policy_sql.") + (".$policy_cancel_sql.") - (".$recon_sql.") RECON_DIFF";
		$this->_db->query($sql, $params);
		if($this->_db->error()){
			return -1;
		}else{
			$result = $this->_db->first();
			return $result->RECON_DIFF;
		}
	}
	
	public function getPoliciesInMonth($month, $year, $user_id, $user_role) {
		$sql = "SELECT
				  po.ID POLICY_ID,
				  po.CREATION_DATE POLICY_COMPLETE_DATE,
				  po.POLICY_NUMBER POLICY_NUMBER,
				  ofr.TCKN TCKN, 
				  ofr.VERGI VERGI,
				  ofr.DESCRIPTION EK_BILGI,
				  (SELECT NAME FROM USER WHERE ID = ofr.USER_ID) BRANCH_NAME,
				  ofr.USER_ID BRANCH_ID,
				  ofre.USER_ID PERSONEL_ID,
				  po.USER_ID PERSONEL_ID_POLICY,
				  co.NAME COMPANY_NAME,
				  co.ID COMPANY_ID,
				  ofr.POLICY_TYPE POLICY_TYPE,
				  ofre.PRIM PRIM, 
				  ofre.KOMISYON KOMISYON, 
				  ofre.PROD_KOMISYON PROD_KOMISYON
				FROM 
				  OFFER_REQUEST ofr, 
				  OFFER_REQUEST_COMPANY orc, 
				  OFFER_RESPONSE ofre, 
				  COMPANY co,
				  POLICY po 
				WHERE ofr.ID = orc.REQUEST_ID 
				  AND ofre.ID = orc.OFFER_ID 
				  AND co.ID = orc.COMPANY_ID
				  AND po.ID = orc.POLICY_ID 
				  AND MONTH(po.CREATION_DATE) = ?
				  AND YEAR(po.CREATION_DATE) = ?";
		$params = array($month, $year);
		
		if($user_role == User::BRANCH){
			$sql .= " AND ofr.USER_ID = ?";
			array_push($params, $user_id);
		}
		if($user_role == User::PERSONEL){
			$sql .= " AND (ofre.USER_ID = ? OR po.USER_ID = ?)";
			array_push($params, $user_id);
			array_push($params, $user_id);
		}
		
		$this->_db->query($sql, $params);
		$result = $this->_db->all();
		if(is_null($result)){
			return null;
		}else{
			$allPolicies = array();
			foreach ($result as $object){
				$policy = json_decode(json_encode($object), true);
				array_push($allPolicies, $policy);
			}
				
			return $allPolicies;
		}
	}

	public function insertRecon($reconPolicy){
		$sql = "INSERT INTO RECON(TAKIP_NO,TANZIM_TARIHI,POLICE_NO,TCKN,VERGI_NO,EK_BILGI,PRODUKTOR, ";
		$sql .= "PRODUKTOR_ID,TEKNIKCI_ID,TEKNIKCI_ID_POLICY,SIRKET,SIRKET_ID,POLICE_TURU,BRUT,KOMISYON,PROD_KOMISYON,PARA_BIRIMI) ";
		$sql .= "VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		
		$params = array(
				$reconPolicy[ReconPolicy::POLICY_ID],
				$reconPolicy[ReconPolicy::POLICY_COMPLETE_DATE],
				$reconPolicy[ReconPolicy::POLICY_NUMBER],
				$reconPolicy[ReconPolicy::TCKN],
				$reconPolicy[ReconPolicy::VERGI],
				$reconPolicy[ReconPolicy::EK_BILGI],
				$reconPolicy[ReconPolicy::BRANCH_NAME],
				$reconPolicy[ReconPolicy::BRANCH_ID],
				$reconPolicy[ReconPolicy::PERSONEL_ID],
				$reconPolicy[ReconPolicy::PERSONEL_ID_POLICY],
				$reconPolicy[ReconPolicy::COMPANY_NAME],
				$reconPolicy[ReconPolicy::COMPANY_ID],
				$reconPolicy[ReconPolicy::POLICY_TYPE],
				$reconPolicy[ReconPolicy::PRIM],
				$reconPolicy[ReconPolicy::KOMISYON],
				$reconPolicy[ReconPolicy::PROD_KOMISYON],
				'TL'
		);
		
		$this->_db->query($sql, $params);
		if($this->_db->error()){
			return false;
		}else{
			return true;
		}
	}
	
	public function reconExist($policy_id){
		$sql = "SELECT * FROM RECON WHERE TAKIP_NO = ?";
		$this->_db->query($sql, array($policy_id));
		if($this->_db->count() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function getRecons($month, $year, $user_id, $user_role){
		$sql = "SELECT * FROM RECON WHERE MONTH(TANZIM_TARIHI) = ? AND YEAR(TANZIM_TARIHI) = ?";
		$params = array($month, $year);
		
		if($user_role == User::BRANCH){
			$sql .= " AND PRODUKTOR_ID = ?";
			array_push($params, $user_id);
		}
		if($user_role == User::PERSONEL){
			$sql .= " AND (TEKNIKCI_ID = ? OR TEKNIKCI_ID_POLICY = ?)";
			array_push($params, $user_id);
			array_push($params, $user_id);
		}
		
		$this->_db->query($sql, $params);
		$result = $this->_db->all();
		if(is_null($result)){
			return null;
		}else{
			$allRecons = array();
			foreach ($result as $object){
				$recon = json_decode(json_encode($object), true);
				array_push($allRecons, $recon);
			}
		
			return $allRecons;
		}
	}

	public function getRecon($takip_no, $user_id, $user_role){
	$sql = "SELECT * FROM RECON WHERE TAKIP_NO = ?";
		$params = array($takip_no);
		
		if($user_role == User::BRANCH){
			$sql .= " AND PRODUKTOR_ID = ?";
			array_push($params, $user_id);
		}
		if($user_role == User::PERSONEL){
			$sql .= " AND (TEKNIKCI_ID = ? OR TEKNIKCI_ID_POLICY = ?)";
			array_push($params, $user_id);
			array_push($params, $user_id);
		}
		
		$this->_db->query($sql, $params);
		$result = $this->_db->first();
		if(is_null($result)){
			return null;
		}else{
			$recon = json_decode(json_encode($result), true);
			return $recon;
		}
	}

	public function updateRecon($takip_no, $recon_update_params){
		$this->_db->beginTransaction();
		
		$params = array();
		$sql = "UPDATE RECON SET";
		foreach ($recon_update_params as $column => $value){
			$sql .= " ".$column." = ?,";
			array_push($params, $value);
		}
		$sql = substr($sql, 0, -1);//remove last comma(,)
		
		$sql .= " WHERE TAKIP_NO = ?";
		array_push($params, $takip_no);
		
		$this->_db->query($sql, $params);
		if($this->_db->error()){
			$this->_db->rollback();
			return false;
		}else{
			$this->_db->commit();
			return true;
		}
	}
}
?>