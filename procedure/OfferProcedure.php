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
			return null;
		}else{
			$request_id = (int)$this->_db->lastInsertId();
			$sql = "INSERT INTO OFFER_REQUEST_COMPANY(REQUEST_ID, COMPANY_ID) VALUES(?, ?)";
			foreach ($companies as $companyId){
				$this->_db->query($sql, array($request_id, $companyId));
				if($this->_db->error()){
					$this->_db->rollback();
					return null;
				}
			}
			$this->_db->commit();
			return $request_id;
		}
	}
	
	public function getOfferRequest($request_id){
		$sql = "SELECT * FROM OFFER_REQUEST WHERE ID = ?";
		$this->_db->query($sql, array($request_id));
		$result = $this->_db->first();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "offer request[".$request_id."] not found in DB");
			return null;
		}else{
			$sql = "SELECT * FROM OFFER_REQUEST_COMPANY of, COMPANY c WHERE of.COMPANY_ID = c.ID and of.REQUEST_ID = ?";
			$this->_db->query($sql, array($request_id));
			$result2 = $this->_db->all();
			
			$companies = array();
			foreach ($result2 as $object){
				$company = array();
				$company[Company::ID] = $object->ID;
				$company[Company::NAME] = $object->NAME;
				array_push($companies, $company);
			}
			
			$offerRequest = array();
			$offerRequest[OfferRequest::ID] = $request_id;
			$offerRequest[OfferRequest::USER_ID] = $result->USER_ID;
			$offerRequest[OfferRequest::CREATION_DATE] = $result->CREATION_DATE;
			$offerRequest[OfferRequest::PLAKA] = $result->PLAKA;
			$offerRequest[OfferRequest::TCKN] = $result->TCKN;
			$offerRequest[OfferRequest::VERGI] = $result->VERGI;
			$offerRequest[OfferRequest::BELGE] = $result->BELGE;
			$offerRequest[OfferRequest::ASBIS] = $result->ASBIS;
			$offerRequest[OfferRequest::COMPANIES] = $companies;
			
			return $offerRequest;
		}
	}
	
	public function getAllRequests($user_id){
		$sql = "SELECT * FROM OFFER_REQUEST WHERE USER_ID = ? ORDER BY CREATION_DATE DESC";
		$this->_db->query($sql, array($user_id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "offer request[".$request_id."] not found in DB");
			return null;
		}else{
			$allOffers = array();
			foreach ($result as $offerObject){
				$sql = "SELECT * FROM OFFER_REQUEST_COMPANY of, COMPANY c WHERE of.COMPANY_ID = c.ID and of.REQUEST_ID = ?";
				$this->_db->query($sql, array($offerObject->ID));
				$result2 = $this->_db->all();
				
				$companies = array();
				foreach ($result2 as $object){
					$company = array();
					$company[Company::ID] = $object->ID;
					$company[Company::NAME] = $object->NAME;
					array_push($companies, $company);
				}
				
				$offerRequest = array();
				$offerRequest[OfferRequest::ID] = $offerObject->ID;
				$offerRequest[OfferRequest::USER_ID] = $offerObject->USER_ID;
				$offerRequest[OfferRequest::CREATION_DATE] = $offerObject->CREATION_DATE;
				$offerRequest[OfferRequest::PLAKA] = $offerObject->PLAKA;
				$offerRequest[OfferRequest::TCKN] = $offerObject->TCKN;
				$offerRequest[OfferRequest::VERGI] = $offerObject->VERGI;
				$offerRequest[OfferRequest::BELGE] = $offerObject->BELGE;
				$offerRequest[OfferRequest::ASBIS] = $offerObject->ASBIS;
				$offerRequest[OfferRequest::COMPANIES] = $companies;
				
				array_push($allOffers, $offerRequest);
			}
				
			return $allOffers;
		}
	}
	
}

?>
