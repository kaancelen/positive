<?php 

include_once (__DIR__.'/../procedure/SearchProcedures.php');
include_once (__DIR__.'/Service.php');

class SearchService implements Service{
	
	private $_searchProcedures;
	
	public function __construct(){
		$this->_searchProcedures = new SearchProcedures();
	}
	
	public function searchRequest($request_id, $requester, $user_id){
		$response = $this->_searchProcedures->searchRequest($request_id, $requester, $user_id);
		return array(1 => array($response));
	}
	
	public function searchOffer($offer_id, $requester, $user_id){
		$response = $this->_searchProcedures->searchOffer($offer_id, $requester, $user_id);
		return array(2 => array($response));
	}
	
	public function searchPolicy($policy_id, $requester, $user_id){
		$response = $this->_searchProcedures->searchPolicy($policy_id, $requester, $user_id);
		return array(3 => array($response));
	}
	
	public function searchExtend($plaka_no, $tckn, $vergi_no, $belge_no, $asbis, $requester, $user_id){
		return $this->_searchProcedures->searchExtend($plaka_no, $tckn, $vergi_no, $belge_no, $asbis, $requester, $user_id);
	}
	
	public function checkNewOfferPolicy($companies, $last_enter_offer_req, $last_enter_policy_req){
		return $this->_searchProcedures->checkNewOfferPolicy($companies, $last_enter_offer_req, $last_enter_policy_req);
	}
	
	public function checkNewPolicyRequest($request_id){
		return $this->_searchProcedures->checkNewPolicyRequest($request_id);
	}

	public function checkNewPolicy($user_id, $last_enter_policy_page, $last_enter_offer_resp){
		return $this->_searchProcedures->checkNewPolicy($user_id, $last_enter_policy_page, $last_enter_offer_resp);
	}

	public function checkNewCancelRequest($last_enter_policy_req){
		return $this->_searchProcedures->checkNewCancelRequest($last_enter_policy_req);
	}

	public function checkNewCancelResponse($user_id, $last_enter_policy_req){
		return $this->_searchProcedures->checkNewCancelResponse($user_id, $last_enter_policy_req);
	}

	public function getPoliciesInMonth($month, $year){
		return $this->_searchProcedures->getPoliciesInMonth($month, $year);
	}

	public function getOffersInMonth($month, $year){
		return $this->_searchProcedures->getOffersInMonth($month, $year);
	}
}

?>