<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/offerRequest.php');

class OfferProcedures extends Procedures{

	const TAG = "OfferProcedures";

	public function __construct(){
		parent::__construct();
	}
	
	public function addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $user_id, $companies){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO OFFER_REQUEST(PLAKA, TCKN, VERGI, BELGE, ASBIS, USER_ID) VALUES(?,?,?,?,?,?)";
		$this->_db->query($sql, array($plaka, $tckn, $vergi, $belge, $asbis, $user_id));
		
		if($this->_db->error()){
			$this->_db->rollback();
			return false;
		}else{
			$request_id = (int)$this->_db->lastInsertId();
			$sql = "INSERT INTO OFFER_REQUEST_COMPANY(REQUEST_ID, COMPANY_ID) VALUES(?, ?)";
			foreach ($companies as $companyId){
				$this->_db->query($sql, array($request_id, $companyId));
				if($this->_db->error()){
					$this->_db->rollback();
					return false;
				}
			}
			$this->_db->commit();
			return true;
		}
		
	}
	
}

?>
