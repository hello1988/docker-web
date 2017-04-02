<?php
include_once dirname(__FILE__)."/../lib/logMgr.php";
include_once dirname(__FILE__)."/../lib/util.php";

class postHandle
{
	private $req = null;
	private $resp = null;
	
	private $dbConn = null;
	
	public function handle()
	{
		logMgr::writeLog( "please override handle method" );
	}

	public function __construct( $reqObj, $respObj )
	{
		$this->req = $reqObj;
		$this->resp = $respObj;
		logMgr::writeLog( json_encode($_POST) );
		
		if ( !array_key_exists( "arg", $_POST ) ) 
		{
			setErrorCode(error::$ARG_NOT_FOUND);
			return ;
		}
		
		$this->req->setDataByJson( $_POST["arg"] );
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
		// $this->dbConn->query("use items;");
	}
	
	public function queryDB( $sqlStr )
	{
		$this->checkConn();
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
		echo json_encode(get_object_vars($this));
	}
}
?>
