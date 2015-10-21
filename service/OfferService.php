<?php
include_once (__DIR__.'/../procedure/OfferProcedure.php');
include_once (__DIR__.'/../classes/offerRequest.php');
include_once (__DIR__.'/Service.php');

class OfferService implements Service{
	
	private $_offerProcedures;
	
	public function __construct(){
		$this->_offerProcedures = new OfferProcedures();
	}
	
	public function getPersonelRequests($companies, $limit = null, $show_completed = false){
		return $this->_offerProcedures->getPersonelRequests($companies, $limit, $show_completed);
	}
	
	public function getBranchRequests($user_id){
		return $this->_offerProcedures->getBranchRequests($user_id);
	}
	
	public function addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $policy_type, $user_id, $companies){
		return $this->_offerProcedures->addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $policy_type, $user_id, $companies);
	}
	
	public function getOfferRequest($request_id){
		return $this->_offerProcedures->getOfferRequest($request_id);
	}
	
	public function getAllRequests($user_id = null, $companies = null, $limit = null, $showCompleted = true){
		return $this->_offerProcedures->getAllRequests($user_id, $companies, $limit, $showCompleted);
	}
	
	public function addOffer($user_id, $request_id, $company_id, $prim, $komisyon, $prod_komisyon){
		return $this->_offerProcedures->addOffer($user_id, $request_id, $company_id, $prim, $komisyon, $prod_komisyon);
	}
	
	public function getOffers($request_id){
		return $this->_offerProcedures->getOffers($request_id);
	}
	
	public function getOffer($offer_id){
		return $this->_offerProcedures->getOffer($offer_id);
	}
	
	public function addCardInfos($offer_id, $name, $card_no, $expire_date, $cvc){
		return $this->_offerProcedures->addCardInfos($offer_id, $name, $card_no, $expire_date, $cvc);
	}
	
	public function getAllPolicyRequest($user_id = null, $month, $year, $allowed_comp){
		return $this->_offerProcedures->getAllPolicyRequest($user_id, $month, $year, $allowed_comp);
	}
	
	public function getPolicyRequest($offer_id, $user_id = null){
		return $this->_offerProcedures->getPolicyRequest($offer_id, $user_id);
	}
	
	public function addPolicy($request_id, $offer_id, $card_id, $policyPath, $makbuzPath, $user_id, $policy_number, $policy_ek_bilgi){
		return $this->_offerProcedures->addPolicy($request_id, $offer_id, $card_id, $policyPath, $makbuzPath, $user_id, $policy_number, $policy_ek_bilgi);
	}
	
	public function getCompletedPolicies($user_id = null, $month, $year, $allowed_comp){
		return $this->_offerProcedures->getCompletedPolicies($user_id, $month, $year, $allowed_comp);
	}
	
	public function getCompletedPolicy($policy_id, $user_id = null){
		return $this->_offerProcedures->getCompletedPolicy($policy_id, $user_id);
	}
	
	public function getGivenOfferRatio($request_id){
		return $this->_offerProcedures->getGivenOfferRatio($request_id);
	}

	public function closeRequest($request_id, $status_code){
		return $this->_offerProcedures->closeRequest($request_id, $status_code);
	}

	public function removeOffer($talep_no, $company_id, $offer_id){
		return $this->_offerProcedures->removeOffer($talep_no, $company_id, $offer_id);
	}

	public function openRequest($request_id){
		return $this->_offerProcedures->openRequest($request_id);
	}
}

?>