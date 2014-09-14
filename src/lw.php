<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Clio\Console;

class Lw 
{
	protected $log;
	
	public function __construct()
	{
		$this->log = new Logger('log');
		$this->log->pushHandler(new StreamHandler('log.txt', Logger::INFO));
	}
	
	public function debug($msg)
	{
		$this->_log('debug', '%w', '%0', $msg);
	}
	
	public function info($msg)
	{
		$this->_log('info', '%w', '%0', $msg);
	}
	
	public function notice($msg)
	{
		$this->_log('notice',  '%y', '%0', $msg);
	}
	
	public function warning($msg)
	{
		$this->_log('warning', '%m', '%0', $msg);
	}
	
	public function error($msg)
	{
		$this->_log('error', '%r', '%0', $msg);
	}
	
	public function critical($msg)
	{
		$this->_log('critical', '%R', '%0', $msg);
	}
	
	public function alert($msg)
	{
		$this->_log('alert', '%R', '%0', $msg);
	}
	
	public function emergency($msg)
	{
		$this->_log('emergency', '%W', '%1', $msg);
	}
	
	public function _log($type, $color, $background, $msg)
	{
		Console::output($color.$background.$msg.'%n');
		call_user_func_array(array($this->log, "add".ucfirst($type)), array($msg));
	}
	
}