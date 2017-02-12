<?php

// 只接收post
if( $_SERVER["REQUEST_METHOD"] != "POST" )
{
	return;
}

include_once dirname(__FILE__)."/../lib/requestMgr.php";

header("Content-Type: application/json;charset=utf-8");
requestMgr::getInstance()->handle($_POST["cmdID"]);

?>
