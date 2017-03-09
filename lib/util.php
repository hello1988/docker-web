<?php

class util
{
	public static function getPR()
	{
		$seed = "abcdefghijklmnoprqstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		return substr( str_shuffle($seed), 0, 20 );
	}
	
}

?>
