<?php

class logMgr
{
	public static function writeLog( $msg )
	{
		// $str = date('[Y/M/d H:i:s]')." ".$msg."\n";
		// file_put_contents('/var/log/httpd/php.log', $str, FILE_APPEND);
		echo $msg."\n";
	}
	
}

?>
