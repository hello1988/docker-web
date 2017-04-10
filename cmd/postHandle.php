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

	public function __construct( $reqObj, $respObj )
	{
		$this->req = $reqObj;
		$this->resp = $respObj;
		logMgr::writeLog( json_encode($_POST) );
		
		if ( !array_key_exists( "data", $_POST ) ) 
		{
			setErrorCode(error::$ARG_NOT_FOUND);
			return ;
		}
		
		$decodeData = $this->req->decodeData( $_POST["data"] );
		$this->req->setDataByJson( $decodeData );
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
}

class reqBase
{
	// client 加密 先guest在base
	// 先用base解開 再用guest解
	public function decodeData( $base64Data )
	{
		// 前44碼為時戳+key的加密
		$seed_key = substr($base64Data, 0, 44);
		$encodeData = substr($base64Data, 44);
		
		$seed = util::doDecoder(postHandle::$BASE_KEY, $seed_key);
		$decodeData = util::doDecoder($seed, $encodeData);
		
		return $decodeData;
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
	
}

class respBase
{
	private $errorCode = 0;
	
	public function __construct()
	{
		$this->errorCode = error::$NONE;
	}
	public function setErrorCode($code){$this->errorCode = $code;}
	public function getErrorCode(){return $this->errorCode;}
	
	public function writeResp()
	{
		// get_object_vars 為了把private的成員也印出來
		$value_org_json = json_encode(get_object_vars($this));
		$seed = util::getGuestKey();
		$seed_key = util::doEncoder(postHandle::$BASE_KEY, $seed);
		$respStr = $seed_key.util::doEncoder($seed, $value_org_json);
		
		$result = array();
		$result["data"] = $respStr;
		echo json_encode($result);
	}
}
?>
