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
		$policy_cancel_sql = "SELECT COUNT(*) FROM CANCEL_REQUEST can WHERE can.STATUS = 1 AND MONTH(can.COMPLETE_DATE) = ?";
		$policy_cancel_sql .= " AND YEAR(can.COMPLETE_DATE) = ?";
		array_push($params, $month);
		array_push($params, $year);
		if($user_role == User::BRANCH){
			$policy_cancel_sql .= " AND can.USER_ID = ?";
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
				  ofre.PROD_KOMISYON PROD_KOMISYON,
				  co.IC_DIS KAYNAK,
				  co.URETIM_KANALI,
				  ofre.UST_KOMISYON UST_KOMISYON,
				  ofre.BAGLI_KOMISYON BAGLI_KOMISYON,
				  (SELECT NAME FROM USER WHERE ID = (SELECT UST_ACENTE FROM AGENT_RELATION WHERE ACENTE = ofr.USER_ID)) UST_ACENTE,
				  (SELECT NAME FROM USER WHERE ID = (SELECT BAGLI_ACENTE FROM AGENT_RELATION WHERE ACENTE = ofr.USER_ID)) BAGLI_ACENTE
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

	public function getPolicyCancelsInMonth($month, $year, $user_id, $user_role){
		$sql = "SELECT
					((-1)*can.ID) POLICY_ID,
					can.COMPLETE_DATE POLICY_COMPLETE_DATE,
					can.POLICY_NUMBER POLICY_NUMBER,
					can.EK_BILGI EK_BILGI,
					(SELECT NAME FROM USER WHERE ID = can.USER_ID) BRANCH_NAME,
					can.USER_ID BRANCH_ID,
					can.PERSONEL_ID PERSONEL_ID,
					co.NAME COMPANY_NAME,
					co.ID COMPANY_ID,
					can.POLICY_TYPE POLICY_TYPE,
					co.IC_DIS KAYNAK,
					co.URETIM_KANALI
				FROM 
					CANCEL_REQUEST can,
					COMPANY co
				WHERE can.COMPANY_ID = co.ID
					AND STATUS = 1
					AND MONTH(COMPLETE_DATE) = ?
					AND YEAR(COMPLETE_DATE) = ?";
		$params = array($month, $year);
		
		if($user_role == User::BRANCH){
			$sql .= " AND can.USER_ID = ?";
			array_push($params, $user_id);
		}
		if($user_role == User::PERSONEL){
			$sql .= " AND can.PERSONEL_ID = ?";
			array_push($params, $user_id);
		}
		
		$this->_db->query($sql, $params);
		$result = $this->_db->all();
		if(is_null($result)){
			return null;
		}else{
			$allPolicyCancels = array();
			foreach ($result as $object){
				$policyCancel = json_decode(json_encode($object), true);
				array_push($allPolicyCancels, $policyCancel);
			}
		
			return $allPolicyCancels;
		}
	}
	
	public function insertRecon($reconPolicy){
		$sql = "INSERT INTO RECON(URETIM_IPTAL,TAKIP_NO,TANZIM_TARIHI,KAYNAK,URETIM_KANALI,MUSTERI_TIPI,POLICE_NO, ";
		$sql .= "TCKN,VERGI_NO,EK_BILGI,BAGLI,PRODUKTOR,PRODUKTOR_ID,UST_PRODUKTOR,UST_PRODUKTOR_KOMISYON,TEKNIKCI_ID, ";
		$sql .= "TEKNIKCI_ID_POLICY,SIRKET,SIRKET_ID,POLICE_TURU,PARA_BIRIMI,BRUT,KOMISYON,TAHSILAT_DURUMU,PROD_KOMISYON,BAGLI_KOMISYON) ";
		$sql .= "VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		
		$uretim = "ÜRETİM";
		$kapali = "KAPALI";
		if(!empty($reconPolicy[ReconPolicy::TCKN])){
			$musteriTipi = "Bireysel";
		}else if(!empty($reconPolicy[ReconPolicy::VERGI])){
			$musteriTipi = "Kurumsal";
		}else{
			$musteriTipi = "";
		}
		
		$params = array(
				$uretim,
				$reconPolicy[ReconPolicy::POLICY_ID],
				$reconPolicy[ReconPolicy::POLICY_COMPLETE_DATE],
				$reconPolicy[ReconPolicy::KAYNAK],
				$reconPolicy[ReconPolicy::URETIM_KANALI],
				$musteriTipi,
				$reconPolicy[ReconPolicy::POLICY_NUMBER],
				$reconPolicy[ReconPolicy::TCKN],
				$reconPolicy[ReconPolicy::VERGI],
				$reconPolicy[ReconPolicy::EK_BILGI],
				$reconPolicy[ReconPolicy::BAGLI_ACENTE],
				$reconPolicy[ReconPolicy::BRANCH_NAME],
				$reconPolicy[ReconPolicy::BRANCH_ID],
				$reconPolicy[ReconPolicy::UST_ACENTE],
				$reconPolicy[ReconPolicy::UST_KOMISYON],
				$reconPolicy[ReconPolicy::PERSONEL_ID],
				$reconPolicy[ReconPolicy::PERSONEL_ID_POLICY],
				$reconPolicy[ReconPolicy::COMPANY_NAME],
				$reconPolicy[ReconPolicy::COMPANY_ID],
				$reconPolicy[ReconPolicy::POLICY_TYPE],
				'TL',
				$reconPolicy[ReconPolicy::PRIM],
				$reconPolicy[ReconPolicy::KOMISYON],
				$kapali,
				$reconPolicy[ReconPolicy::PROD_KOMISYON],
				$reconPolicy[ReconPolicy::BAGLI_KOMISYON]
		);
		
		$this->_db->query($sql, $params);
		if($this->_db->error()){
			return false;
		}else{
			return true;
		}
	}
	
	public function insertReconCancel($reconCancel){
	$sql = "INSERT INTO RECON(URETIM_IPTAL,TAKIP_NO,TANZIM_TARIHI,POLICE_NO,EK_BILGI,PRODUKTOR, ";
		$sql .= "PRODUKTOR_ID,TEKNIKCI_ID,SIRKET,SIRKET_ID,POLICE_TURU,PARA_BIRIMI, ";
		$sql .= "URETIM_KANALI,KAYNAK,TAHSILAT_DURUMU)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		
		$iptal = "İPTAL";
		$kapali = "KAPALI";
		$params = array(
				$iptal,
				$reconCancel[ReconPolicy::POLICY_ID],
				$reconCancel[ReconPolicy::POLICY_COMPLETE_DATE],
				$reconCancel[ReconPolicy::POLICY_NUMBER],
				$reconCancel[ReconPolicy::EK_BILGI],
				$reconCancel[ReconPolicy::BRANCH_NAME],
				$reconCancel[ReconPolicy::BRANCH_ID],
				$reconCancel[ReconPolicy::PERSONEL_ID],
				$reconCancel[ReconPolicy::COMPANY_NAME],
				$reconCancel[ReconPolicy::COMPANY_ID],
				$reconCancel[ReconPolicy::POLICY_TYPE],
				'TL',
				$reconCancel[ReconPolicy::URETIM_KANALI],
				$reconCancel[ReconPolicy::KAYNAK],
				$kapali
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