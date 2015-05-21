<?php
include_once(__DIR__.'/Log4php/Logger.php');

/**
 * Class ALogger
 * Singleton pattern used
 */
class ALogger {

    const FATAL = 'FATAL';
    const ERROR = 'ERROR';
    const WARN = 'WARN';
    const INFO = 'INFO';
    const DEBUG = 'DEBUG';
    const TRACE = 'TRACE';

    private static $_instance = null;

    private $_logger;

    #Constructer, its private Because we never call new Logger() only use getInstance outside
    private function __construct(){
        Logger::configure(__DIR__.'/logger_config.xml');
        $this->_logger = Logger::getLogger('logger');
    }

    #@Override
    public static function getInstance(){
        if(!isset(self::$_instance)){ #for singleton feature
            self::$_instance = new ALogger();
        }
        return self::$_instance;
    }

    public function write($level, $tag, $message){
        $msg = "[".$tag."] ".$message;

        switch($level){
            case self::FATAL : $this->_logger->fatal($msg);break;
            case self::ERROR : $this->_logger->error($msg);break;
            case self::WARN : $this->_logger->warn($msg);break;
            case self::INFO : $this->_logger->info($msg);break;
            case self::DEBUG : $this->_logger->debug($msg);break;
            case self::TRACE : $this->_logger->trace($msg);break;
            default : return;
        }
    }

}