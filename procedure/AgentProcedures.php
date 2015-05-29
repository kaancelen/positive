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
			return false;
		}else{
			$sql = "UPDATE USER SET FIRST_LOGIN = 0 WHERE ID = ?";
			$this->_db->query($sql, array($params[Agent::USER_ID]));
			return true;
		}
	}
	
	public function update($params){
		$sql = "UPDATE AGENT_DETAIL SET EXECUTIVE=?, ADDRESS=?, PHONE=?, FAX=?, GSM=?, EMAIL=?, IBAN=?, BANK=?, AGENTS=? ";
		$sql .= "WHERE USER_ID = ?";
		
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
			return false;
		}else{
			$sql = "UPDATE USER SET FIRST_LOGIN = 0 WHERE ID = ?";
			$this->_db->query($sql, array($params[Agent::USER_ID]));
			return true;
		}
	}
}

?>