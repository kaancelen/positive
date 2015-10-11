<?php

include_once (__DIR__.'/../Util/util.php');
include_once (__DIR__.'/../Logger/ALogger.php');

class DB{

	private static $_instance = null;
	private $_pdo;			#php database object
	private $_query; 			#sql query
	private $_error = false;	#error message
	private $_results;			#query result
	private $_count;
	private $_logger;
	
	const TAG = "DB";
	
	public static function getInstance(){
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	// Constructor
	private function __construct() {
		$this->_logger = ALogger::getInstance();
		try {
			$this->_pdo = new PDO("mysql:host=localhost;dbname=positive", "root", "");
			$this->query("SET NAMES UTF8");//prevent turkish char problem
		}catch(PDOException $e){
			$this->_logger->write(ALogger::ERROR, self::TAG, $e->getMessage());
			die($e->getMessage());
		}
	}
	
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }
	
	//Open transaction
	public function beginTransaction(){
		$this->_pdo->beginTransaction();
	}
	
	//Commit it
	public function commit(){
		$this->_pdo->commit();
	}
	
	//Rollback all
	public function rollback(){
		$this->_pdo->rollBack();
	}
	
	#execute given query
	#query("SELECT * FROM users WHERE username = ? AND groups = ?", array('kaan', 2))
	public function query($sql, $params = array(), $attr_emulate_prepares = null){
		$this->_error = false;	#maybe previous error stay
		if(!is_null($attr_emulate_prepares)){
			$this->_pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );//int bind value error
		}
		if($this->_query = $this->_pdo->prepare($sql)){
			$x = 1;
			if(count($params)){
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count =$this->_query->rowCount();
			}else{
				$this->_error = true;
			}
		}
		return $this;
	}
	
	#last insert id
	public function lastInsertId($name = null){
		return $this->_pdo->lastInsertId($name);
	}
	#first result of last query
	public function first(){
		$all = $this->_results;
		return (empty($all) ? null : $all[0]);
	}
	#all result of last query
	public function all(){
		return $this->_results;
	}
	#return last error
	public function error(){
		return $this->_error;
	}
	#count of last query result set element number
	public function count(){
		return $this->_count;
	}
}

?>