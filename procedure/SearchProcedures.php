<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/Search.php');
include_once (__DIR__.'/../classes/User.php');

class SearchProcedures extends Procedures{
	
	const TAG = "SearchProcedures";
	
	public function __construct(){
		parent::__construct();
	}
	
	public function searchRequest($request_id, $requester, $user_id){
		$params = array($request_id);
		$sql = "SELECT us.NAME BRANCH_NAME FROM OFFER_REQUEST ofr, USER us WHERE us.ID = ofr.USER_ID AND ofr.ID = ?";
		if($requester == User::BRANCH){
			$sql .= " AND ofr.USER_ID = ?";
			array_push($params, $user_id);
		}
		$this->_db->query($sql, $params);
		
		$result = $this->_db->first();
		if(is_null($result)){
			return null;
		}else{
			$offerRequest = array(
					Search::BRANCH_NAME => $result->BRANCH_NAME,
					Search::LINK => '/positive/'.($requester == User::BRANCH ? "branch" : "personel" ).'/offer.php?request_id='.$request_id
			);
			return $offerRequest;
		}
	}
	
	public function searchOffer($offer_id, $requester, $user_id){
		$params = array($offer_id);
		$sql = "SELECT (SELECT NAME FROM USER WHERE ID = ofr.USER_ID) BRANCH_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = ofre.USER_ID) PERSONEL_NAME FROM OFFER_REQUEST ofr, ";
		$sql .= "OFFER_RESPONSE ofre, OFFER_REQUEST_COMPANY orc WHERE orc.REQUEST_ID = ofr.ID AND ";
		$sql .= "orc.OFFER_ID = ofre.ID AND orc.CARD_ID <> 0 AND ofre.ID = ?";
		
		if($requester == User::BRANCH){
			$sql .= " AND ofr.USER_ID = ?";
			array_push($params, $user_id);
		}
		$this->_db->query($sql, $params);
		
		$result = $this->_db->first();
		if(is_null($result)){
			return null;
		}else{
			$policyRequest = array(
					Search::BRANCH_NAME => $result->BRANCH_NAME,
					Search::PERSONEL_NAME => $result->PERSONEL_NAME,
					Search::LINK => '/positive/'.($requester == User::BRANCH ? "branch" : "personel" ).'/policyReqDetails.php?offer_id='.$offer_id
			);
			return $policyRequest;
		}
	}
	
	public function searchPolicy($policy_id, $requester, $user_id){
		$params = array($policy_id, $policy_id);
		$sql = "SELECT po.ID POLICY_ID, (SELECT NAME FROM USER WHERE ID = ofr.USER_ID) BRANCH_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = ofre.USER_ID) PERSONEL_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = po.USER_ID) POLICY_COMPLETE_PERSONEL ";
		$sql .= "FROM OFFER_REQUEST ofr,OFFER_RESPONSE ofre, OFFER_REQUEST_COMPANY orc, POLICY po ";
		$sql .= "WHERE orc.REQUEST_ID = ofr.ID AND orc.OFFER_ID = ofre.ID AND orc.POLICY_ID = po.ID ";
		$sql .= "AND (po.ID = ? OR po.POLICY_NUMBER = ?)";
		
		if($requester == User::BRANCH){
			$sql .= " AND ofr.USER_ID = ?";
			array_push($params, $user_id);
		}
		$this->_db->query($sql, $params);
		
		$result = $this->_db->first();
		if(is_null($result)){
			return null;
		}else{
			$real_policy_id = $result->POLICY_ID;
			$policyRequest = array(
					Search::BRANCH_NAME => $result->BRANCH_NAME,
					Search::PERSONEL_NAME => $result->PERSONEL_NAME,
					Search::POLICY_COMPLETE_PERSONEL => $result->POLICY_COMPLETE_PERSONEL,
					Search::LINK => '/positive/'.($requester == User::BRANCH ? "branch" : "personel" ).'/policyDetail.php?policy_id='.$real_policy_id
			);
			return $policyRequest;
		}
	}
	
	public function searchExtend($plaka_no, $tckn, $vergi_no, $belge_no, $asbis, $requester, $user_id){
		$request_list = array();
		$offer_list = array();
		$policy_list = array();
		
		$params = array();
		$sql = "SELECT ID FROM OFFER_REQUEST WHERE 1=1 ";
		if(!empty($plaka_no)){
			$sql .= " AND PLAKA LIKE ?";
			array_push($params, "%".$plaka_no."%");
		}
		if(!empty($tckn)){
			$sql .= " AND TCKN = ?";
			array_push($params, $tckn);
		}
		if(!empty($vergi_no)){
			$sql .= " AND VERGI = ?";
			array_push($params, $vergi_no);
		}
		if(!empty($belge_no)){
			$sql .= " AND BELGE = ?";
			array_push($params, $belge_no);
		}
		if(!empty($asbis)){
			$sql .= " AND ASBIS = ?";
			array_push($params, $asbis);
		}
		if($requester == User::BRANCH){
			$sql .= " AND USER_ID = ?";
			array_push($params, $user_id);
		}
		$this->_db->query($sql, $params);
		
		$allResult = $this->_db->all();
		if(is_null($allResult)){
			return null;
		}else{
			foreach ($allResult as $request_result){
				//Pull requests
				$request_id = $request_result->ID;
				array_push($request_list, $this->searchRequest($request_id, $requester, $user_id));
				//find offer id of request
				$sql = "SELECT ofre.ID OFFER_ID FROM OFFER_REQUEST ofr, OFFER_RESPONSE ofre, ";
				$sql .= "OFFER_REQUEST_COMPANY orc WHERE orc.REQUEST_ID = ofr.ID AND orc.OFFER_ID = ofre.ID ";
				$sql .= "AND orc.CARD_ID <> 0 AND ofr.ID = ?";
				$this->_db->query($sql, array($request_id));
				$offer_result = $this->_db->first();
				if(is_null($offer_result)){
					continue;
				}else{
					//Pull offer
					$offer_id = $offer_result->OFFER_ID;
					array_push($offer_list, $this->searchOffer($offer_id, $requester, $user_id));
					//find policy id of offer
					$sql = "SELECT POLICY_ID FROM OFFER_REQUEST_COMPANY WHERE OFFER_ID = ? AND POLICY_ID <> 0";
					$this->_db->query($sql, array($offer_id));
					$policy_result = $this->_db->first();
					if(is_null($policy_result)){
						continue;
					}else{
						//pull policy
						$policy_id = $policy_result->POLICY_ID;
						array_push($policy_list, $this->searchPolicy($policy_id, $requester, $user_id));
					}
				}
			}
			
			$response = array(
					1 => $request_list,
					2 => $offer_list,
					3 => $policy_list
			);
			
			return $response;
		}
	}
	
	public function checkNewOfferPolicy($companies, $last_enter_offer_req, $last_enter_policy_req){
		$sql = "SELECT ofr.ID FROM OFFER_REQUEST ofr, OFFER_REQUEST_COMPANY orc WHERE ";
		$sql .= "ofr.ID = orc.REQUEST_ID AND orc.COMPANY_ID IN (";
		foreach ($companies as $id){
			$sql .= "?,";
		}
		$sql = substr($sql, 0, -1);//remove last ','
		$sql .= ") AND ofr.CREATION_DATE > ?";
		
		$params = array();
		foreach ($companies as $id){
			array_push($params, $id);
		}
		array_push($params, $last_enter_offer_req);
		$this->_db->query($sql, $params);
		$offer_req_count = $this->_db->count();
		
		$sql = "SELECT cc.ID FROM OFFER_REQUEST_COMPANY orc, CREDIT_CARDS cc WHERE cc.ID = orc.CARD_ID AND ";
		$sql .= "orc.POLICY_ID = 0 AND orc.COMPANY_ID IN (";
		foreach ($companies as $id){
			$sql .= "?,";
		}
		$sql = substr($sql, 0, -1);//remove last ','
		$sql .= ") AND cc.CREATION_DATE > ?";
		
		$params = array();
		foreach ($companies as $id){
			array_push($params, $id);
		}
		array_push($params, $last_enter_policy_req);
		$this->_db->query($sql, $params);
		$policy_req_count = $this->_db->count();
		
		return array($offer_req_count, $policy_req_count);
	}
}

?>