<?php
include_once dirname(__FILE__)."/logMgr.php";
// include_once dirname(__FILE__)."/cmd.php";

class requestMgr
{
	private static $instance = null;
	
	private $cmdMap = null;
	private function requestMgr()
	{
		$this->cmdMap = array
		(
			"1" => "cmd",
			
			"1000" => "cmdLogin",
		);
	}
	
	public static function getInstance()
	{
		if( requestMgr::$instance == null )
		{
			requestMgr::$instance = new requestMgr();
		}
		return requestMgr::$instance;
	}
	
	/*
	public function registCmd( $cmdID, $className )
	{
		if (array_key_exists( $cmdID, $this->cmdMap)) 
		{
			logMgr::writeLog( "[requestMgr][handle] cmdID duplicated : $cmdID" );
			return ;
		}
		
		$this->cmdMap[$cmdID] = array($className, "handle")
	}
	*/
	
	public function handle( $cmdID )
	{
		logMgr::writeLog( "[requestMgr][handle] : cmdID($cmdID) args(".json_encode($_POST).")" );
		if (!array_key_exists( $cmdID, $this->cmdMap)) 
		{
			logMgr::writeLog( "[requestMgr][handle] cmdID err : $cmdID" );
			return ;
		}

		// 只載入我要的class 並呼叫handle
		$className = $this->cmdMap[$cmdID];
		$fileName = "/".$className.".php";
		include_once dirname(__FILE__).$fileName;
		
		$refClass = new ReflectionClass($className);
		$refObj = $refClass->newInstance();
		$refObj->handle();
	}
}
?>
