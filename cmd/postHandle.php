<?php
include_once dirname(__FILE__)."/../lib/logMgr.php";
include_once dirname(__FILE__)."/../lib/util.php";

class postHandle
{
	private $req = null;
	private $resp = null;
	
	private $dbConn = null;
	
	public static $BASE_KEY = "4099efc3825d55e58b97f6f84fddac00";
	
	public function handle()
	{
		logMgr::writeLog( "please override handle method" );
	}

	public function init( $reqObj, $respObj, $guestKey = "" )
	{
		$this->req = $reqObj;
		$this->resp = $respObj;
		logMgr::writeLog( json_encode($_POST) );
		
		if ( !array_key_exists( "data", $_POST ) ) 
		{
			$this->setErrorCode(error::$ARG_NOT_FOUND);
			return false;
		}
		
		if( $guestKey == "" )
		{
			if ( !array_key_exists( "UserID", $_POST ) ) 
			{
				$this->setErrorCode(error::$ARG_NOT_FOUND);
				return false;
			}
			$userID = $_POST["UserID"];
			$guestKey = queryGuestKeyByUserID( $userID );
			
			if( $guestKey == "" )
			{
				$this->setErrorCode(error::$ARG_NOT_FOUND);
				return false;
			}
		}
		
		$this->req->setGuestKey( $guestKey );
		$this->resp->setGuestKey( $guestKey );
		
		$decodeData = $this->req->decodeData( $_POST["data"] );
		if($decodeData == "")
		{
			$this->setErrorCode(error::$ARG_NOT_FOUND);
			return false;
		}
		$this->req->setDataByJson( $decodeData );
		return true;
	}
	
	public function __destruct() 
	{
		$this->closeConn();
	}
	
	public function checkConn() 
	{
		if( $this->dbConn != null )
		{
			return ;
		}
		
		$servername = getenv("MYSQL_PORT_3306_TCP_ADDR");
		$username = "root";
		$password = "password";

		// Create connection
		$this->dbConn = new mysqli($servername, $username, $password);

		// Check connection
		if (!$this->dbConn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		
		// use database
		$this->dbConn->query("use AirCleaner;");
	}
	
	public function queryDB( $sqlStr )
	{
		$this->checkConn();
		// echo "queryDB : ".$sqlStr."\n";
		// echo "dbConn : ".$this->dbConn->connect_errno."\n";
		// var_dump($this->dbConn);
		return $this->dbConn->query($sqlStr);
	}
	
	public function closeConn()
	{
		if( $this->dbConn == null )
		{
			return ;
		}
		mysqli_close($this->dbConn);
	}

	public function setErrorCode( $code )
	{
		$this->resp->setErrorCode($code);
	}
	
	public function writeResp()
	{
		$this->resp->writeResp();
	}
	
	public function getReq()
	{
		return $this->req;
	}
	
	public function getResp()
	{
		return $this->resp;
	}
	
		
	public function queryGuestKeyByUserID( $userID )
	{
		$sql = util::string_format("select guest_key from User where User_ID = '{0}' limit 1;",$userID);
		$result->queryDB( $sqlStr );
		
		if($result->num_rows<= 0)
		{
			return "";
		}
		
		$dbRowData = $result->fetch_assoc();
		// var_dump( $dbRowData );
		return $dbRowData["guest_key"];
	}
}

class reqBase
{	
	private $guestKey = "";
	public function setGuestKey( $gKey )
	{
		$this->guestKey = $gKey;
	}
	// client 加密 先guest在base
	// 先用base解開 再用guest解
	public function decodeData( $base64Data )
	{
		// 前44碼為時戳+key的加密
		$jsonStr = $this->keyDecode( postHandle::$BASE_KEY, $base64Data );
		$data = json_decode($jsonStr,true)["data"];
		// logMgr::writeLog( "jsonStr : ".$jsonStr );
		// logMgr::writeLog( "data : ".$data );
		
		$jsonStr = $this->keyDecode( $this->guestKey, $data );
		return $jsonStr;
	}
	
	public function setDataByJson($jsonStr)
	{
		$data = json_decode($jsonStr, true);
		if( $data == null)
		{
			return false;
		}
		foreach ($data as $key => $value)
		{
			$this->{$key} = $value;
		}
		
		return true;
	}
	
	protected function keyDecode( $key, $value )
	{
		// 前44碼為時戳+key的加密
		$seed_key = substr($value, 0, 44);
		$encodeData = substr($value, 44);
		
		$seed = util::doDecoder($key, $seed_key);
		$data = util::doDecoder($seed, $encodeData);
		
		return $data;
	}
}

class respBase
{
	private $ErrorCode = 0;
	private $guestKey = "";
	
	public function __construct()
	{
		$this->ErrorCode = error::$NONE;
	}
	public function setErrorCode($code){$this->ErrorCode = $code;}
	public function getErrorCode(){return $this->ErrorCode;}
	
	public function setGuestKey( $gKey )
	{
		$this->guestKey = $gKey;
	}

	public function writeResp()
	{
		// get_object_vars 為了把private的成員也印出來
		$respStr = $this->keyEncode( $this->guestKey, json_encode(get_object_vars($this)) );
		$result = array();
		$result["data"] = $respStr;
		
		$respStr2 = $this->keyEncode( postHandle::$BASE_KEY, json_encode($result) );
		
		echo $respStr2 ;
	}
	
	protected function keyEncode( $key, $value )
	{
		$seed = md5( util::getGuestKey(32) );
		$seed_key = util::doEncoder(postHandle::$BASE_KEY, $seed);
		$base64Str = $seed_key.util::doEncoder($seed, $value);
		return $base64Str;
	}
}
?>
