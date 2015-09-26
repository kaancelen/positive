<?php
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/agent.php');

class AgentProcedures extends Procedures{
	
	const TAG = "AgentProcedures";
	
	public function __construct(){
		parent::__construct();
	}
	
	public function exist($user_id){
		$sql = "SELECT USER_ID FROM AGENT_DETAIL WHERE USER_ID = ?";
		$this->_db->query($sql, array($user_id));
		
		$result = $this->_db->count();
		
		if($result > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function get($user_id){
		$sql = "SELECT * FROM AGENT_DETAIL WHERE USER_ID = ?";
		$this->_db->query($sql, array($user_id));
		
		$result = $this->_db->first();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "Agent[".$user_id."] not found in DB");
			return null;
		}else{
			$agent = array();
			$agent[Agent::USER_ID] = $user_id;
			$agent[Agent::EXECUTIVE] = $result->EXECUTIVE;
			$agent[Agent::ADDRESS] = $result->ADDRESS;
			$agent[Agent::PHONE] = $result->PHONE;
			$agent[Agent::FAX] = $result->FAX;
			$agent[Agent::GSM] = $result->GSM;
			$agent[Agent::EMAIL] = $result->EMAIL;
			$agent[Agent::IBAN] = $result->IBAN;
			$agent[Agent::BANK] = $result->BANK;
			$agent[Agent::AGENTS] = $result->AGENTS;
			
			return $agent;
		}
	}
	
	public function add($params){
		$sql = "INSERT INTO AGENT_DETAIL(USER_ID, EXECUTIVE, ADDRESS, PHONE, FAX, GSM, EMAIL, IBAN, BANK, AGENTS) ";
		$sql .= "VALUES(?,?,?,?,?,?,?,?,?,?)";
		
		$this->_db->beginTransaction();
		$this->_db->query($sql, array(
				$params[Agent::USER_ID],
				$params[Agent::EXECUTIVE],
				$params[Agent::ADDRESS],
				$params[Agent::PHONE],
				$params[Agent::FAX],
				$params[Agent::GSM],
				$params[Agent::EMAIL],
				$params[Agent::IBAN],
				$params[Agent::BANK],
				$params[Agent::AGENTS]
		));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_db->rollback();
			return false;
		}else{
			$sql = "UPDATE USER SET FIRST_LOGIN = 0 WHERE ID = ?";
			$this->_db->query($sql, array($params[Agent::USER_ID]));
			$result = $this->_db->all();
			if(is_null($result)){
				$this->_db->rollback();
				return false;
			}else{
				$this->_db->commit();
				return true;
			}
		}
	}
	
	public function update($params){
		$sql = "UPDATE AGENT_DETAIL SET EXECUTIVE=?, ADDRESS=?, PHONE=?, FAX=?, GSM=?, EMAIL=?, IBAN=?, BANK=?, AGENTS=? ";
		$sql .= "WHERE USER_ID = ?";
		
		$this->_db->beginTransaction();
		$this->_db->query($sql, array(
				$params[Agent::EXECUTIVE],
				$params[Agent::ADDRESS],
				$params[Agent::PHONE],
				$params[Agent::FAX],
				$params[Agent::GSM],
				$params[Agent::EMAIL],
				$params[Agent::IBAN],
				$params[Agent::BANK],
				$params[Agent::AGENTS],
				$params[Agent::USER_ID]
		));
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_db->rollback();
			return false;
		}else{
			$sql = "UPDATE USER SET FIRST_LOGIN = 0 WHERE ID = ?";
			$this->_db->query($sql, array($params[Agent::USER_ID]));
			$result = $this->_db->all();
			if(is_null($result)){
				$this->_db->rollback();
				return false;
			}else{
				$this->_db->commit();
				return true;
			}
		}
	}
	
	public function updateCardInfo($card_id, $card_name, $card_no, $expire_date, $cvc){
		$sql = "UPDATE CREDIT_CARDS SET NAME = ?, CARD_NO = ?, EXPIRE_DATE = ?, CVC_CODE = ? WHERE ID = ?";
		$this->_db->query($sql, array($card_name, $card_no, $expire_date, $cvc, $card_id));
		$error = $this->_db->error();
		return $error;
	}

	public function changePolicyAgent($request_id, $offer_id, $new_user_id){
		$this->_db->beginTransaction();
		$sql = "UPDATE OFFER_REQUEST SET USER_ID = ? WHERE ID = ?";
		$this->_db->query($sql, array($new_user_id, $request_id));
		if($this->_db->error()){
			$this->_db->rollback();
			return false;
		}else{
			$sql = "UPDATE OFFER_RESPONSE SET PROD_KOMISYON = (KOMISYON*(SELECT KOMISYON_RATE FROM USER WHERE ID = ?))/100";
			$sql .= " WHERE ID = ?";
			$this->_db->query($sql, array($new_user_id, $offer_id));
			if($this->_db->error()){
				$this->_db->rollback();
				return false;
			}else{
				$sql = "UPDATE RECON SET 
						PRODUKTOR = (SELECT NAME FROM USER WHERE ID = ?), 
						PRODUKTOR_ID = ?, 
						PROD_KOMISYON = (SELECT PROD_KOMISYON FROM OFFER_RESPONSE WHERE ID = ?)
						WHERE TAKIP_NO = (SELECT POLICY_ID FROM OFFER_REQUEST_COMPANY 
											WHERE REQUEST_ID = ? AND OFFER_ID = ?)";
				$this->_db->query($sql, array($new_user_id, $new_user_id, $offer_id, $request_id, $offer_id));
				if($this->_db->error()){
					$this->_db->rollback();
					return false;
				}else{
					$this->_db->commit();
					return true;
				}
			}
		}
		
	}
}

?>