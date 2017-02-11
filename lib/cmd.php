<?php
include dirname(__FILE__)."/postHandle.php";

class cmd extends postHandle
{
	public static function handle()
	{
		echo "cmd public method ";
		echo $_POST["data"];
	}
}

?>
