<?php

class util
{
	private static $seed = "abcdefghijklmnoprqstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
	public static function getToken( $len )
	{
		// $muti = max(ceil( strlen(util::$seed)/$len ),1);
		$size = min(strlen(util::$seed), $len);
		return substr( str_shuffle(util::$seed), 0, $size );
	}
	
	private static $guestKeySeed = "0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef";
	public static function getGuestKey( $len = 32)
	{
		return substr( str_shuffle(util::$guestKeySeed), 0, $len );
	}
	
	public static function createUserID()
	{
		// 規則未定
		
		$header = "ACR";	// 3
		$t = time();		// 11
		$tail = util::getToken( 7 );// 7

		// time 前面補0到11位
		$t = "00000000000".$t;
		$t = substr($t, strlen($t)-11, 11);

		$UserID = util::string_format("{0}{1}{2}", $header, $t, $tail);
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
	public static function string_format()
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
	
	public static function doEncoder($key, $value)
	{
		logMgr::writeLog( "doEncoder( ".$key.", ".$value.")" );
		$resultStr = "";

		$key_len = strlen($key);
		$value_len = strlen($value);
		for ($i = 0; $i < $value_len; $i++)
		{
			// value 依序跟 key每個字元做xor
			$resultStr .= chr(ord($value[$i]) ^ ord($key[$i%$key_len]));
		}

		logMgr::writeLog( "doEncoder resultStr : ".base64_encode($resultStr) );
		return base64_encode($resultStr);
	}
	
	public static function doDecoder($key, $value)
	{
		// logMgr::writeLog( "doDecoder( ".$key.", ".$value.")" );
		$resultStr = "";
		
		$decodeValue = base64_decode($value);
		$key_len = strlen($key);
		$value_len = strlen($decodeValue);
		for ($i = 0; $i < $value_len; $i++)
		{
			// 反解的字串跟原本的key做xor
			$resultStr .= chr(ord($decodeValue[$i]) ^ ord($key[$i%$key_len]));
		}

		// logMgr::writeLog( "doDecoder resultStr : ".$resultStr );
		return $resultStr;
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
