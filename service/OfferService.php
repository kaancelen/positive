<?php
include_once (__DIR__.'/../procedure/OfferProcedure.php');
include_once (__DIR__.'/../classes/offerRequest.php');
include_once (__DIR__.'/Service.php');

class OfferService implements Service{
	
	private $_offerProcedures;
	
	public function __construct(){
		$this->_offerProcedures = new OfferProcedures();
	}
	
	public function addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $user_id, $companies){
		return $this->_offerProcedures->addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $user_id, $companies);
	}
	
	public function getOfferRequest($request_id){
		return $this->_offerProcedures->getOfferRequest($request_id);
	}
	
	public function getAllRequests($user_id = null, $all = null){
		return $this->_offerProcedures->getAllRequests($user_id, $all);
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
	
	public function getAllPolicyRequest($user_id = null){
		return $this->_offerProcedures->getAllPolicyRequest($user_id);
	}
	
	public function getPolicyRequest($offer_id, $user_id = null){
		return $this->_offerProcedures->getPolicyRequest($offer_id, $user_id);
	}
}

?>