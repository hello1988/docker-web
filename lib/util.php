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
	
	public static function createUserID()
	{
		// 規則未定
		
		$header = "ACR";	// 3
		$t = time();		// 11
		$tail = util::getOTP( 7 );// 7

		// time 前面補0到11位
		$t = "00000000000".$t;
		$t = substr($t, strlen($t)-11, 11);

		$UserID = Util::string_format("{0}{1}{2}", $header, $t, $tail);
		return $UserID;
	}
	
	public static function setJsonToObject( $obj, $jsonStr )
	{
		$data = json_decode($jsonStr, true);
		foreach ($data as $key => $value)
		{
			$obj->{$key} = $value;
		}
	}
	
	// ex : string_format("aa{2}bb{0}cc{1}",11,22,33); => aa33bb11cc22
	function string_format()
	{
		$args = func_get_args();
		$argNum = count($args);
		if ($argNum == 0) { return '';}     
		if ($argNum == 1) { return $args[0]; }
	
		return preg_replace_callback
			(
				"/\{(\\d)\}/",
				function($m) use($args, $argNum) 
				{
					$index = $m[1]+1;
					return (($index > 0) && ($index < $argNum)) ? $args[$index]:"";
				},
				$args[0]
			);
	}
}

class error
{
	public static $NONE = 0;
	
	public static $ARG_NOT_FOUND = 1;	// post 參數沒有arg
	public static $ARG_NOT_JSON  = 2;	// arg 不是正確的json格式
	
	public static $USER_NOT_FOUND= 100;	// 帳號 - 找不到使用者
	
}
?>
