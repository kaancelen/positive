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
}

?>