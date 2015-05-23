<?php 

include_once (__DIR__.'/../db/db.php');
include_once (__DIR__.'/../Logger/ALogger.php');

class Procedures{
	
	protected $_db;
	protected $_logger;
	
	public function __construct(){
		$this->_logger = ALogger::getInstance();
		$this->_db = DB::getInstance();
	}
	
}

?>