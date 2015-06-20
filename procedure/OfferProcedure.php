<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/offerRequest.php');
include_once (__DIR__.'/../classes/policyRequest.php');

class OfferProcedures extends Procedures{

	const TAG = "OfferProcedures";

	public function __construct(){
		parent::__construct();
	}
	/**
	 * @param unknown $plaka
	 * @param unknown $tckn
	 * @param unknown $vergi
	 * @param unknown $belge
	 * @param unknown $asbis
	 * @param unknown $description
	 * @param unknown $user_id
	 * @param unknown $companies
	 * @return NULL|number
	 */
	public function addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $user_id, $companies){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO OFFER_REQUEST(PLAKA, TCKN, VERGI, BELGE, ASBIS, USER_ID, DESCRIPTION) VALUES(?,?,?,?,?,?,?)";
		$this->_db->query($sql, array($plaka, $tckn, $vergi, $belge, $asbis, $user_id, $description));
		
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
	/**
	 * @param unknown $request_id
	 * @return NULL|multitype:unknown multitype: NULL
	 */
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
			$offerRequest[OfferRequest::DESCRIPTION] = $result->DESCRIPTION;
			$offerRequest[OfferRequest::COMPANIES] = $companies;
			
			return $offerRequest;
		}
	}
	/**
	 * @param unknown $user_id
	 * @param unknown $all
	 * @return NULL|multitype:
	 */
	public function getAllRequests($user_id, $all){
		$user_id_array = null;
		$user_id_part = " ";
		$status_part = " ";
		if(!is_null($user_id)){
			$user_id_part = " WHERE USER_ID = ? ";
			$user_id_array = array($user_id);
			if(!is_null($all)){
				$status_part = " AND STATUS = 0 ";
			}
		}else{
			if(!is_null($all)){
				$status_part = " WHERE STATUS = 0 ";
			}
		}
		$sql = "SELECT * FROM OFFER_REQUEST".$user_id_part.$status_part."ORDER BY CREATION_DATE DESC";
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
				$offerRequest[OfferRequest::DESCRIPTION] = $offerObject->DESCRIPTION;
				$offerRequest[OfferRequest::COMPANIES] = $companies;
				
				array_push($allOffers, $offerRequest);
			}
				
			return $allOffers;
		}
	}
	/**
	 * @param unknown $user_id
	 * @param unknown $request_id
	 * @param unknown $company_id
	 * @param unknown $prim
	 * @param unknown $komisyon
	 * @return NULL|number
	 */
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
	/**
	 * @param unknown $request_id
	 * @return NULL|multitype:
	 */
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
	/**
	 * @param unknown $offer_id
	 * @return NULL|multitype:NULL
	 */
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
	/**
	 * @param unknown $offer_id
	 * @param unknown $name
	 * @param unknown $card_no
	 * @param unknown $expire_date
	 * @param unknown $cvc
	 * @return NULL|number
	 */
	public function addCardInfos($offer_id, $name, $card_no, $expire_date, $cvc){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO CREDIT_CARDS(NAME, CARD_NO, EXPIRE_DATE, CVC_CODE) VALUES(?,?,?,?)";
		$this->_db->query($sql, array($name, $card_no, $expire_date, $cvc));
		if($this->_db->error()){
			$this->_db->rollback();
			return null;
		}else{
			$card_id = (int)$this->_db->lastInsertId();
			$sql = "UPDATE OFFER_REQUEST_COMPANY SET CARD_ID = ? WHERE OFFER_ID = ?";
			$this->_db->query($sql, array($card_id, $offer_id));
			if($this->_db->error()){
				$this->_db->rollback();
				return null;
			}else{
				$sql = "UPDATE OFFER_REQUEST SET STATUS = 1 WHERE ID = ";
				$sql .= "(SELECT REQUEST_ID FROM OFFER_REQUEST_COMPANY WHERE OFFER_ID = ? LIMIT 1)";
				$this->_db->query($sql, array($offer_id));
				if($this->_db->error()){
					$this->_db->rollback();
					return null;
				}
			}
				
			$this->_db->commit();
			return $card_id;
		}
	}
	/**
	 * @param string $user_id
	 * @param string $all
	 */
	public function getAllPolicyRequest($user_id){
		$sql = "SELECT ofr.ID REQUEST_ID, ofre.ID OFFER_ID, ofre.USER_ID PERSONEL_ID, ofr.ID BRANCH_ID, ofre.PRIM, ofre.KOMISYON, ";
		$sql .= "ofre.CREATION_DATE OFFER_DATE, ofr.PLAKA, co.NAME COMPANY_NAME FROM OFFER_REQUEST ofr, OFFER_REQUEST_COMPANY orc, ";
		$sql .= "OFFER_RESPONSE ofre,COMPANY co WHERE ofr.ID = orc.REQUEST_ID AND ofre.ID = orc.OFFER_ID ";
		$sql .= "AND co.ID = orc.COMPANY_ID AND (ofr.USER_ID = ? OR ofre.USER_ID = ?) AND orc.CARD_ID = 1";
		
		$this->_db->query($sql, array($user_id, $user_id));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "policy requests[".$user_id."] not found in DB");
			return null;
		}else{
			$allPolicyRequests = array();
			foreach ($result as $object){
				$policyRequest = array();
				$policyRequest[PolicyRequest::REQUEST_ID] = $object->REQUEST_ID;
				$policyRequest[PolicyRequest::OFFER_ID] = $object->OFFER_ID;
				$policyRequest[PolicyRequest::PERSONEL_ID] = $object->PERSONEL_ID;
				$policyRequest[PolicyRequest::BRANCH_ID] = $object->BRANCH_ID;
				$policyRequest[PolicyRequest::OFFER_DATE] = $object->OFFER_DATE;
				$policyRequest[PolicyRequest::PLAKA] = $object->PLAKA;
				$policyRequest[PolicyRequest::COMPANY_NAME] = $object->COMPANY_NAME;
				$policyRequest[PolicyRequest::PRIM] = $object->PRIM;
				$policyRequest[PolicyRequest::KOMISYON] = $object->KOMISYON;
				
				$sql = "SELECT NAME FROM USER WHERE ID = ?";
				$this->_db->query($sql, array($policyRequest[PolicyRequest::PERSONEL_ID]));
				if(!$this->_db->error()){
					$personel_user = $this->_db->first();
					$policyRequest[PolicyRequest::PERSONEL_NAME] = $personel_user->NAME;
						
					$this->_db->query($sql, array($policyRequest[PolicyRequest::BRANCH_ID]));
					if(!$this->_db->error()){
						$branch_user = $this->_db->first();
						$policyRequest[PolicyRequest::BRANCH_NAME] = $branch_user->NAME;
					}else{
						$this->_logger->write(ALogger::DEBUG, self::TAG, "BRANCH_NAME [".$policyRequest[PolicyRequest::BRANCH_NAME]."] not found in DB");
						return null;
					}
				}else{
					$this->_logger->write(ALogger::DEBUG, self::TAG, "PERSONEL_NAME [".$policyRequest[PolicyRequest::PERSONEL_NAME]."] not found in DB");
					return null;
				}
				
				array_push($allPolicyRequests, $policyRequest);
			}
			
			return $allPolicyRequests;
		}
		
	}
}

?>
