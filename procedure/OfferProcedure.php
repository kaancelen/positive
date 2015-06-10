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
		$user_id_array = null;
		$user_id_part = " ";
		if(!is_null($user_id)){
			$user_id_part = " WHERE USER_ID = ? ";
			$user_id_array = array($user_id);
		}
		$sql = "SELECT * FROM OFFER_REQUEST".$user_id_part."ORDER BY CREATION_DATE DESC";
		$this->_db->query($sql, $user_id_array);
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
	
	public function addOffer($user_id, $request_id, $company_id, $prim, $komisyon){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO OFFER_RESPONSE(USER_ID, PRIM, KOMISYON) VALUES(?,?,?)";
		$this->_db->query($sql, array($user_id, $prim, $komisyon));
		if($this->_db->error()){
			$this->_db->rollback();
			return null;
		}else{
			$offer_id = (int)$this->_db->lastInsertId();
			$sql = "UPDATE OFFER_REQUEST_COMPANY SET OFFER_ID = ? WHERE REQUEST_ID = ? AND COMPANY_ID = ?";
			$this->_db->query($sql, array($offer_id, $request_id, $company_id));
			if($this->_db->error()){
				$this->_db->rollback();
				return null;
			}
			
			$this->_db->commit();
			return $offer_id;
		}
	}
	
	public function getOffers($request_id){
		$sql = "SELECT * FROM OFFER_RESPONSE ofr, OFFER_REQUEST_COMPANY orc WHERE ofr.ID = orc.OFFER_ID AND orc.REQUEST_ID = ?";
		$this->_db->query($sql, array($request_id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "offer request[".$request_id."] not found in DB");
			return null;
		}else{
			$allOffers = array();
			foreach ($result as $offerObject){
				$offerResponse = array();
				$offerResponse[OfferResponse::ID] = $offerObject->ID;
				$offerResponse[OfferResponse::USER_ID] = $offerObject->USER_ID;
				$offerResponse[OfferResponse::PRIM] = $offerObject->PRIM;
				$offerResponse[OfferResponse::KOMISYON] = $offerObject->KOMISYON;
				$offerResponse[OfferResponse::COMPANY_ID] = $offerObject->COMPANY_ID;
				$offerResponse[OfferResponse::REQUEST_ID] = $offerObject->REQUEST_ID;
				
				array_push($allOffers, $offerResponse);
			}
			return $allOffers;
		}
	}
	
	public function getOffer($offer_id){
		$sql = "SELECT * FROM OFFER_RESPONSE ofr, OFFER_REQUEST_COMPANY orc WHERE ofr.ID = orc.OFFER_ID AND ofr.ID = ?";
		$this->_db->query($sql, array($offer_id));
		$result = $this->_db->first();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "offer request[".$request_id."] not found in DB");
			return null;
		}else{
			$offer = array();
			$offer[OfferResponse::ID] = $result->ID;
			$offer[OfferResponse::USER_ID] = $result->USER_ID;
			$offer[OfferResponse::PRIM] = $result->PRIM;
			$offer[OfferResponse::KOMISYON] = $result->KOMISYON;
			$offer[OfferResponse::COMPANY_ID] = $result->COMPANY_ID;
			$offer[OfferResponse::REQUEST_ID] = $result->REQUEST_ID;
			
			return $offer;
		}
	}
	
}

?>
