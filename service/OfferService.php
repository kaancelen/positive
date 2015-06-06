<?php
include_once (__DIR__.'/../procedure/OfferProcedure.php');
include_once (__DIR__.'/../classes/offerRequest.php');
include_once (__DIR__.'/Service.php');

class OfferService implements Service{
	
	private $_offerProcedures;
	
	public function __construct(){
		$this->_offerProcedures = new OfferProcedures();
	}
	
	public function addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $user_id, $companies){
		return $this->_offerProcedures->addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $user_id, $companies);
	}
	
	public function getOfferRequest($request_id){
		return $this->_offerProcedures->getOfferRequest($request_id);
	}
	
	public function getAllRequests($user_id = null){
		return $this->_offerProcedures->getAllRequests($user_id);
	}
	
	public function addOffer($user_id, $request_id, $company_id, $prim, $komisyon){
		return $this->_offerProcedures->addOffer($user_id, $request_id, $company_id, $prim, $komisyon);
	}
	
	public function getOffers($request_id){
		return $this->_offerProcedures->getOffers($request_id);
	}
}

?>