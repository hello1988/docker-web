<?php
include_once dirname(__FILE__)."/postHandle.php";
include_once dirname(__FILE__)."/../lib/logMgr.php";
include_once dirname(__FILE__)."/../lib/util.php";
include_once dirname(__FILE__)."/../lib/sqlUser.php";

class cmdLogin extends postHandle
{
	public function handle()
	{
		logMgr::writeLog( "[cmdLogin][handle]" );
		$req = $this->getReq();
		$resp = $this->getResp();
		
		// echo $req->UserName." : ".$req->Password."\n";
		
		// $sql = "select * from User where User_ID = 'aaa';";
		// $result = $this->queryDB( $sql );
		// $result->free();
		$user = $this->createUser($req->UserName,$req->Password);
		if( $user != null)
		{
			$resp->UserID = $req->UserName;
			$resp->Token = $user->otp;	
		}
		$this->writeResp();
	}
	
	private function createUser($userName, $password)
	{
		$newUser = User::createUser( "email", $userName, "phone", $password, $userName );
		$insertSql = $newUser->getInsertSql();
		
		if( !$this->queryDB( $insertSql ) )
		{
			$resp = $this->getResp();
			// $resp->setErrorCode(error::);
			return null;
		}

		return $newUser;
	}
	
	public function __construct()
	{
		$reqObj = new reqLogin();
		$respObj = new respLogin();
		parent::__construct( $reqObj, $respObj );
	}
}

class reqLogin extends reqBase
{
    public $UserName = "";
    public $Password = "";
}

class respLogin extends respBase
{
    public $UserID = 0;
    public $Token = 0;
}


// 只接收post
if( $_SERVER["REQUEST_METHOD"] != "POST" )
{
	return;
}

$login = new cmdLogin();
$login->handle();
?>
