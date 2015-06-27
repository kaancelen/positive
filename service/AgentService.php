<?php
include_once (__DIR__.'/../procedure/AgentProcedures.php');
include_once (__DIR__.'/../classes/agent.php');
include_once (__DIR__.'/Service.php');

class AgentService implements Service{
	
	private $_agentProcedures;
	
	public function __construct(){
		$this->_agentProcedures = new AgentProcedures();
	}
	
	public function update($params){
		if($this->_agentProcedures->exist($params[Agent::USER_ID])){
			$this->_agentProcedures->update($params);
		}else{
			$this->_agentProcedures->add($params);
		}
	}
	
	public function get($user_id){
		return $this->_agentProcedures->get($user_id);
	}
	
	public function updateCardInfo($card_id, $card_name, $card_no, $expire_date, $cvc){
		return $this->_agentProcedures->updateCardInfo($card_id, $card_name, $card_no, $expire_date, $cvc);
	}
	
}

?>