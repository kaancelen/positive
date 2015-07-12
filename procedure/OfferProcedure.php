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
	public function addOfferRequest($plaka, $tckn, $vergi, $belge, $asbis, $description, $policy_type, $user_id, $companies){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO OFFER_REQUEST(PLAKA, TCKN, VERGI, BELGE, ASBIS, USER_ID, DESCRIPTION, POLICY_TYPE) VALUES(?,?,?,?,?,?,?,?)";
		$this->_db->query($sql, array($plaka, $tckn, $vergi, $belge, $asbis, $user_id, $description, $policy_type));
		
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
			$offerRequest[OfferRequest::POLICY_TYPE] = $result->POLICY_TYPE;
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
	public function getAllRequests($time, $user_id, $all){
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
		$sql = "SELECT * FROM OFFER_REQUEST".$user_id_part.$status_part."AND CREATION_DATE >= ".$time." ORDER BY CREATION_DATE ASC";
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
				$offerRequest[OfferRequest::POLICY_TYPE] = $offerObject->POLICY_TYPE;
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
	public function addOffer($user_id, $request_id, $company_id, $prim, $komisyon, $prod_komisyon){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO OFFER_RESPONSE(USER_ID, PRIM, KOMISYON, PROD_KOMISYON) VALUES(?,?,?,?)";
		$this->_db->query($sql, array($user_id, $prim, $komisyon, $prod_komisyon));
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
		$sql = "SELECT orc.OFFER_ID ID, ofr.USER_ID USER_ID, user.NAME PERSONEL_NAME, ofr.PRIM PRIM, ";
		$sql .= "ofr.KOMISYON KOMISYON, ofr.PROD_KOMISYON PROD_KOMISYON, orc.COMPANY_ID COMPANY_ID, ";
		$sql .= "orc.REQUEST_ID REQUEST_ID FROM OFFER_RESPONSE ofr, OFFER_REQUEST_COMPANY orc, ";
		$sql .= "USER user WHERE ofr.ID = orc.OFFER_ID AND ofr.USER_ID = user.ID AND orc.REQUEST_ID = ?";
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
				$offerResponse[OfferResponse::PERSONEL_NAME] = $offerObject->PERSONEL_NAME;
				$offerResponse[OfferResponse::PRIM] = $offerObject->PRIM;
				$offerResponse[OfferResponse::KOMISYON] = $offerObject->KOMISYON;
				$offerResponse[OfferResponse::PROD_KOMISYON] = $offerObject->PROD_KOMISYON;
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
		$sql = "SELECT orc.OFFER_ID ID, ofr.USER_ID USER_ID, user.NAME PERSONEL_NAME, ofr.PRIM PRIM, ";
		$sql .= "ofr.KOMISYON KOMISYON, ofr.PROD_KOMISYON PROD_KOMISYON, orc.COMPANY_ID COMPANY_ID, ";
		$sql .= "orc.REQUEST_ID REQUEST_ID FROM OFFER_RESPONSE ofr, OFFER_REQUEST_COMPANY orc, ";
		$sql .= "USER user WHERE ofr.ID = orc.OFFER_ID AND ofr.USER_ID = user.ID AND orc.OFFER_ID = ?";
		$this->_db->query($sql, array($offer_id));
		$result = $this->_db->first();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "offer request[".$request_id."] not found in DB");
			return null;
		}else{
			$offer = array();
			$offer[OfferResponse::ID] = $result->ID;
			$offer[OfferResponse::USER_ID] = $result->USER_ID;
			$offer[OfferResponse::PERSONEL_NAME] = $result->PERSONEL_NAME;
			$offer[OfferResponse::PRIM] = $result->PRIM;
			$offer[OfferResponse::KOMISYON] = $result->KOMISYON;
			$offer[OfferResponse::PROD_KOMISYON] = $result->PROD_KOMISYON;
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
	 * @param unknown $user_id
	 * @return NULL|multitype:
	 */
	public function getAllPolicyRequest($user_id){
		$paramArray = null;
		
		$sql = "SELECT ofr.ID REQUEST_ID, ofr.POLICY_TYPE, ofre.ID OFFER_ID, (SELECT NAME FROM USER WHERE ID = ofre.USER_ID) ";
		$sql .= "PERSONEL_NAME, (SELECT NAME FROM USER WHERE ID = ofr.USER_ID) BRANCH_NAME, ofre.PRIM, ofre.KOMISYON, ";
		$sql .= "ofre.PROD_KOMISYON, ofre.CREATION_DATE OFFER_DATE, ofr.PLAKA, co.NAME COMPANY_NAME FROM OFFER_REQUEST ofr, ";
		$sql .= "OFFER_REQUEST_COMPANY orc, OFFER_RESPONSE ofre,COMPANY co WHERE ofr.ID = orc.REQUEST_ID AND ";
		$sql .= "ofre.ID = orc.OFFER_ID AND co.ID = orc.COMPANY_ID ";
		
		if(!is_null($user_id)){
			$sql .= "AND (ofr.USER_ID = ? OR ofre.USER_ID = ?) ";
			$paramArray = array($user_id, $user_id);
		}
		
		$sql .= "AND orc.CARD_ID <> 0 AND orc.POLICY_ID = 0 ORDER BY OFFER_DATE DESC";
		
		$this->_db->query($sql, $paramArray);
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "policy requests[".$user_id."] not found in DB");
			return null;
		}else{
			$allPolicyRequests = array();
			foreach ($result as $object){
				$policyRequest = json_decode(json_encode($object), true);
				array_push($allPolicyRequests, $policyRequest);
			}
			
			return $allPolicyRequests;
		}
		
	}
	/**
	 * @param unknown $offer_id
	 * @param unknown $user_id
	 */
	public function getPolicyRequest($offer_id, $user_id){
		$paramArray = array($offer_id);
		
		$sql = "SELECT ofr.ID REQUEST_ID, ofr.POLICY_TYPE POLICY_TYPE, ofr.USER_ID BRANCH_ID, ofr.CREATION_DATE REQUEST_DATE, ofr.PLAKA PLAKA, ";
		$sql .= "ofr.TCKN TCKN, ofr.VERGI VERGI, ofr.BELGE BELGE, ofr.ASBIS ASBIS, ofr.DESCRIPTION EK_BILGI, ofre.ID OFFER_ID, ";
		$sql .= "ofre.USER_ID PERSONEL_ID, ofre.PRIM PRIM, ofre.KOMISYON KOMISYON, ofre.PROD_KOMISYON PROD_KOMISYON, ";
		$sql .= "ofre.CREATION_DATE OFFER_DATE, (SELECT NAME FROM USER WHERE ID = ofre.USER_ID) PERSONEL_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = ofr.USER_ID) BRANCH_NAME, co.NAME COMPANY_NAME, cc.ID CARD_ID, ";
		$sql .= "cc.NAME CARD_NAME, cc.CARD_NO CARD_NO, cc.EXPIRE_DATE EXPIRE_DATE, cc.CVC_CODE CVC_CODE, ";
		$sql .= "cc.CREATION_DATE POLICY_REQ_DATE FROM OFFER_REQUEST ofr, OFFER_REQUEST_COMPANY orc, ";
		$sql .= "OFFER_RESPONSE ofre,COMPANY co, CREDIT_CARDS cc WHERE ofr.ID = orc.REQUEST_ID AND ";
		$sql .= "ofre.ID = orc.OFFER_ID AND co.ID = orc.COMPANY_ID AND cc.ID = orc.CARD_ID AND ofre.ID = ?";
		
		if(!is_null($user_id)){
			$sql .= "AND (ofr.USER_ID = ? OR ofre.USER_ID = ?) ";
			array_push($paramArray, $user_id);
			array_push($paramArray, $user_id);
		}
		
		$this->_db->query($sql, $paramArray);
		$result = $this->_db->first();
		
		if(!is_null($result)){
			$policyReqDetail = json_decode(json_encode($result), true);
			return $policyReqDetail;
		}else{
			$this->_logger->write(ALogger::DEBUG, self::TAG, "policy requests[".$offer_id."] not found in DB");
			return null;
		}
	}
	
	public function addPolicy($request_id, $offer_id, $card_id, $policyPath, $makbuzPath, $user_id, $policy_number, $policy_ek_bilgi){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO POLICY(USER_ID, POLICY_PATH, MAKBUZ_PATH, POLICY_NUMBER, DESCRIPTION) VALUES(?,?,?,?,?)";
		$this->_db->query($sql, array($user_id, $policyPath, $makbuzPath, $policy_number, $policy_ek_bilgi));
		if($this->_db->error()){
			$this->_db->rollback();
			return null;
		}else{
			$policy_id = (int)$this->_db->lastInsertId();
			$sql = "UPDATE OFFER_REQUEST_COMPANY SET POLICY_ID = ? WHERE REQUEST_ID = ? AND OFFER_ID = ? AND CARD_ID = ?";
			$this->_db->query($sql, array($policy_id, $request_id, $offer_id, $card_id));
			if($this->_db->error()){
				$this->_db->rollback();
				return null;
			}else{
				$sql = "UPDATE CREDIT_CARDS SET EXPIRE_DATE = 'XX/XXXX', CVC_CODE = 'XXX',";
				$sql .= " CARD_NO = CONCAT('XXXX XXXX XXXX ',RIGHT(CARD_NO, 4)) WHERE ID = ?";
				$this->_db->query($sql, array($card_id));
				if($this->_db->error()){
					$this->_db->rollback();
					return null;
				}
			}
		}
		
		$this->_db->commit();
		return $policy_id;
	}
	
	public function getCompletedPolicies($user_id = null){
		$paramArray = array();
		
		$sql = "SELECT ofr.PLAKA PLAKA, po.POLICY_NUMBER POLICY_NUMBER,ofr.POLICY_TYPE POLICY_TYPE, (SELECT NAME FROM USER WHERE ID = ofre.USER_ID) PERSONEL_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = ofr.USER_ID) BRANCH_NAME, co.NAME COMPANY_NAME, ";
		$sql .= "po.ID POLICY_ID, po.CREATION_DATE POLICY_COMPLETE_DATE, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = po.USER_ID) POLICY_COMPLETE_PERSONEL ";
		$sql .= "FROM OFFER_REQUEST ofr, OFFER_REQUEST_COMPANY orc, OFFER_RESPONSE ofre, COMPANY co, ";
		$sql .= "POLICY po WHERE ofr.ID = orc.REQUEST_ID AND ofre.ID = orc.OFFER_ID AND ";
		$sql .= "co.ID = orc.COMPANY_ID AND po.ID = orc.POLICY_ID ";

		if(!is_null($user_id)){
			$sql .= "AND (ofr.USER_ID = ? OR ofre.USER_ID = ? OR po.USER_ID = ?) ";
			array_push($paramArray, $user_id);
			array_push($paramArray, $user_id);
			array_push($paramArray, $user_id);
		}
		
		$sql .= "ORDER BY POLICY_COMPLETE_DATE DESC";
		
		$this->_db->query($sql, $paramArray);
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "completed policies [".$user_id."] not found in DB");
			return null;
		}else{
			$allPolicies = array();
			foreach ($result as $object){
				$policy = json_decode(json_encode($object), true);
				array_push($allPolicies, $policy);
			}
				
			return $allPolicies;
		}
	}
	
	public function getCompletedPolicy($policy_id, $user_id){
		$paramArray = array($policy_id);
		
		$sql = "SELECT ofr.ID REQUEST_ID, ofr.USER_ID BRANCH_ID, ofr.CREATION_DATE REQUEST_DATE, ";
		$sql .= "ofr.POLICY_TYPE POLICY_TYPE, po.POLICY_NUMBER POLICY_NUMBER, po.DESCRIPTION POLICE_EK_BILGI, ";
		$sql .= "ofr.PLAKA PLAKA, ofr.TCKN TCKN, ofr.VERGI VERGI, ofr.BELGE BELGE, ofr.ASBIS ASBIS, ";
		$sql .= "ofr.DESCRIPTION EK_BILGI, ofre.ID OFFER_ID, ofre.USER_ID PERSONEL_ID, ";
		$sql .= "ofre.PRIM PRIM, ofre.KOMISYON KOMISYON, ofre.PROD_KOMISYON PROD_KOMISYON, ";
		$sql .= "ofre.CREATION_DATE OFFER_DATE, (SELECT NAME FROM USER WHERE ID = ofre.USER_ID) PERSONEL_NAME, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = ofr.USER_ID) BRANCH_NAME, co.NAME COMPANY_NAME, ";
		$sql .= "cc.ID CARD_ID, cc.NAME CARD_NAME, cc.CARD_NO CARD_NO, cc.EXPIRE_DATE EXPIRE_DATE, ";
		$sql .= "cc.CVC_CODE CVC_CODE, cc.CREATION_DATE POLICY_REQ_DATE, po.ID POLICY_ID, ";
		$sql .= "po.CREATION_DATE POLICY_COMPLETE_DATE, po.POLICY_PATH POLICY_PATH, po.MAKBUZ_PATH MAKBUZ_PATH, ";
		$sql .= "(SELECT NAME FROM USER WHERE ID = po.USER_ID) POLICY_COMPLETE_PERSONEL ";
		$sql .= "FROM OFFER_REQUEST ofr, OFFER_REQUEST_COMPANY orc, OFFER_RESPONSE ofre, COMPANY co, ";
		$sql .= "CREDIT_CARDS cc, POLICY po WHERE ofr.ID = orc.REQUEST_ID AND ofre.ID = orc.OFFER_ID AND ";
		$sql .= "co.ID = orc.COMPANY_ID AND cc.ID = orc.CARD_ID AND po.ID = orc.POLICY_ID AND ";
		$sql .= "orc.POLICY_ID = ? ";
		if(!is_null($user_id)){
			$sql .= "AND (ofr.USER_ID = ? OR ofre.USER_ID = ? OR po.USER_ID = ?)";
			array_push($paramArray, $user_id);
			array_push($paramArray, $user_id);
			array_push($paramArray, $user_id);
		}
		
		$this->_db->query($sql, $paramArray);
		$result = $this->_db->first();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "completed policy [".$policy_id.", user_id: ".$user_id."] not found in DB");
			return null;
		}else{
			$policy = json_decode(json_encode($result), true);
			return $policy;
		}
	}
	
	public function getGivenOfferRatio($request_id){
		$sql = "SELECT COUNT(co.NAME) ISTEK FROM OFFER_REQUEST_COMPANY orc, OFFER_REQUEST ofr, COMPANY co WHERE orc.REQUEST_ID = ofr.ID AND orc.COMPANY_ID = co.ID AND ofr.ID = ?";
		$this->_db->query($sql, array($request_id));
		$result1 = $this->_db->first();
		
		$sql = "SELECT COUNT(co.NAME) TEKLIF FROM OFFER_REQUEST_COMPANY orc, OFFER_REQUEST ofr, COMPANY co WHERE orc.REQUEST_ID = ofr.ID AND orc.COMPANY_ID = co.ID AND ofr.ID = ? AND orc.OFFER_ID <> 0;";
		$this->_db->query($sql, array($request_id));
		$result2 = $this->_db->first();
		
		return ($result2->TEKLIF.'/'.$result1->ISTEK); 
	}
}
?>
