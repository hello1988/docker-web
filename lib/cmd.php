<?php
include_once dirname(__FILE__)."/postHandle.php";
include_once dirname(__FILE__)."/requestMgr.php";
include_once dirname(__FILE__)."/logMgr.php";

class cmd extends postHandle
{
	public static function handle()
	{
		logMgr::writeLog( "[cmd][handle]" );
		echo "{'msg':'Hello World!'}";
	}
}

?>
