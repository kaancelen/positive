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
}

?>