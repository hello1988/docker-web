<?php
include_once dirname(__FILE__)."/postHandle.php";
include_once dirname(__FILE__)."/../lib/logMgr.php";
include_once dirname(__FILE__)."/../lib/util.php";

class cmdLogin extends postHandle
{
	public function handle()
	{
		logMgr::writeLog( "[cmdLogin][handle]" );
		$req = $this->getReq();
		$resp = $this->getResp();
		
		// echo $req->UserName." : ".$req->Password."\n";
		/*
		$sql = "select User_ID, password, otp, login_time from User where User_ID='".$req->UserName."'";
		echo $sql;
		$result = $this->queryDB( $sql );
		$otp = "";
		// test 找不到帳號 先創建一個
		if(!$result)
		{
			$otp = createUser($userName,$pwd);
		}
		// test end
		else
		{
			var_dump($result);	
		}
		*/
		$otp = util::getOTP(30);
		
		$resp->UserID = $req->UserName;
		$resp->Token = $otp;
		$this->writeResp();
	}
	
	private function createUser($userName,$pwd)
	{
		$otp = util::getOTP(30);
		$insertSql = "insert into User (User_ID, user_name, phone, password, update_by, otp ) values('".$userName."','','0912345678','".$pwd."','admin','".$otp."');";
		
		return $otp;
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
