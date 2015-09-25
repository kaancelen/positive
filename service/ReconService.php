<?php 

include_once (__DIR__.'/../procedure/ReconProcedures.php');
include_once (__DIR__.'/Service.php');
include_once (__DIR__.'/../classes/ReconPolicy.php');
include_once (__DIR__.'/../classes/User.php');
include_once (__DIR__.'/../classes/Recon.php');

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

	public function isReconCompleted($user_role, $recon){
		$class = "";
		if($user_role == User::PERSONEL){
			if(!empty($recon[Recon::KAYNAK]) && !empty($recon[Recon::URETIM_KANALI]) &&
				!empty($recon[Recon::MUSTERI_TIPI]) && !empty($recon[Recon::YENI_TECDIT]) &&
				!empty($recon[Recon::PARA_BIRIMI]) && $recon[Recon::NET] != 0){
				$class = "row-offer-completed";
			}
		}
		if($user_role == User::BRANCH){
			if(!empty($recon[Recon::MUSTERI_ADI]) && !empty($recon[Recon::BASLANGIC_TARIHI]) &&
				!empty($recon[Recon::BITIS_TARIHI])){
				$class = "row-offer-completed";
			}
		}
		if($user_role == User::FINANCE){
			if(!empty($recon[Recon::BOLGE]) && !empty($recon[Recon::BAGLI]) &&
				!empty($recon[Recon::TAHSILAT_DURUMU]) && $recon[Recon::HERO_KOMISYON] != 0 && 
				$recon[Recon::MERKEZ] != 0){
				$class = "row-offer-completed";
			}
		}
		
		return $class;
	}
}

?>