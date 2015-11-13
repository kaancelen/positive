<?php 
include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');
include_once (__DIR__.'/Procedures.php');
include_once (__DIR__.'/../classes/company.php');

class CompanyProcedures extends Procedures{
	
	const TAG = "CompanyProcedures";
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getAll(){
		$sql = "SELECT * FROM COMPANY ORDER BY ACTIVE DESC, NAME";
		$this->_db->query($sql);
		$result = $this->_db->all();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "no company recorded in DB");
			return null;
		}else{
			$companies = array();
			foreach ($result as $object){
				array_push($companies, json_decode(json_encode($object), true));
			}
			return $companies;
		}
	}
	
	public function getCompany($company_id){
		$sql = "SELECT * FROM COMPANY WHERE ID = ?";
		$this->_db->query($sql, array($company_id));
		$result = $this->_db->first();
		
		if(is_null($result)){
			$this->_logger->write(ALogger::DEBUG, self::TAG, "no company recorded in DB id = [".$company_id."]");
			return null;
		}else{
			return json_decode(json_encode($result), true);
		}
	}
	
	public function removeCompany($company_id){
		$sql = "DELETE FROM COMPANY WHERE ID = ?";
		$this->_db->query($sql, array($company_id));
		
		if($this->_db->error()){
			return false;
		}else{
			return true;
		}
	}
	
	public function addCompany($active, $name, $ic_dis, $uretim_kanali){
		$this->_db->beginTransaction();
		
		$sql = "INSERT INTO COMPANY(ACTIVE, NAME, IC_DIS, URETIM_KANALI) VALUES(?,?,?,?)";
		$this->_db->query($sql, array($active, $name, $ic_dis, $uretim_kanali));
		
		if($this->_db->error()){
			$this->_db->rollback();
			return null;
		}else{
			$company_id = (int)$this->_db->lastInsertId();
			$this->_db->commit();
			return $company_id;
		}
	}
	
	public function editCompany($company_id, $active, $name, $ic_dis, $uretim_kanali){
		$this->_db->beginTransaction();
		
		$sql = "UPDATE COMPANY SET ACTIVE = ?, NAME = ?, IC_DIS = ?, URETIM_KANALI =? WHERE ID = ?";
		$this->_db->query($sql, array($active, $name, $ic_dis, $uretim_kanali, $company_id));
		
		if($this->_db->error()){
			$this->_db->rollback();
			return false;
		}else{
			$this->_db->commit();
			return true;
		}
	}
}

?>