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
				$company = array();
				$company[Company::ID] = $object->ID;
				$company[Company::NAME] = $object->NAME;
				$company[Company::ACTIVE] = $object->ACTIVE;
				array_push($companies, $company);
			}
			return $companies;
		}
	}
}

?>