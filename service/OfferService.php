<?php
include_once (__DIR__.'/../procedure/OfferProcedure.php');
include_once (__DIR__.'/../classes/offerRequest.php');
include_once (__DIR__.'/Service.php');

class OfferService implements Service{
	
	private $_offerProcedures;
	
	public function __construct(){
		$this->_offerProcedures = new OfferProcedures();
	}
	
	public function addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $policy_type, $user_id, $companies){
		return $this->_offerProcedures->addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $policy_type, $user_id, $companies);
	}
	
	public function getOfferRequest($request_id){
		return $this->_offerProcedures->getOfferRequest($request_id);
	}
	
	public function getAllRequests($time, $user_id = null, $all = null){
		return $this->_offerProcedures->getAllRequests($time, $user_id, $all);
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
	
	public function getAllPolicyRequest($user_id = null, $time = null){
		return $this->_offerProcedures->getAllPolicyRequest($user_id, $time);
	}
	
	public function getPolicyRequest($offer_id, $user_id = null){
		return $this->_offerProcedures->getPolicyRequest($offer_id, $user_id);
	}
	
	public function addPolicy($request_id, $offer_id, $card_id, $policyPath, $makbuzPath, $user_id, $policy_number, $policy_ek_bilgi){
		return $this->_offerProcedures->addPolicy($request_id, $offer_id, $card_id, $policyPath, $makbuzPath, $user_id, $policy_number, $policy_ek_bilgi);
	}
	
	public function getCompletedPolicies($user_id = null, $time = null){
		return $this->_offerProcedures->getCompletedPolicies($user_id, $time);
	}
	
	public function getCompletedPolicy($policy_id, $user_id = null){
		return $this->_offerProcedures->getCompletedPolicy($policy_id, $user_id);
	}
	
	public function getGivenOfferRatio($request_id){
		return $this->_offerProcedures->getGivenOfferRatio($request_id);
	}
}

?>