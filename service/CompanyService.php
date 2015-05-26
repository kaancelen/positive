<?php 

include_once (__DIR__.'/../procedure/CompanyProcedures.php');
include_once (__DIR__.'/Service.php');

class CompanyService implements Service{
	
	private $_companyProcedures;
	
	public function __construct(){
		$this->_companyProcedures = new CompanyProcedures();
	}
	
	public function getAll(){
		return $this->_companyProcedures->getAll();
	}
}

?>