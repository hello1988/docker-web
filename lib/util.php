<?php

class util
{
	private static $seed = "abcdefghijklmnoprqstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
	public static function getOTP( $len )
	{
		// $muti = max(ceil( strlen(util::$seed)/$len ),1);
		$size = min(strlen(util::$seed), $len);
		return substr( str_shuffle(util::$seed), 0, $size );
	}
	
	public static function setJsonToObject( $obj, $jsonStr )
	{
		$data = json_decode($jsonStr, true);
		foreach ($data as $key => $value)
		{
			$obj->{$key} = $value;
		}
	}
}

class error
{
	public static $NONE = 0;
	
	public static $ARG_NOT_FOUND = 1;	// post 參數沒有arg
	public static $ARG_NOT_JSON  = 2;	// arg 不是正確的json格式
}
?>
