<?php
include_once dirname(__FILE__)."/postHandle.php";
include_once dirname(__FILE__)."/logMgr.php";
include_once dirname(__FILE__)."/util.php";

class cmdLogin extends postHandle
{
	public function handle()
	{
		logMgr::writeLog( "[cmdLogin][handle]" );
		echo $this->getPostArg("account");
	}
}

?>
