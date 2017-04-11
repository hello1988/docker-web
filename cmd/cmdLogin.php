<?php
include_once dirname(__FILE__)."/postHandle.php";
include_once dirname(__FILE__)."/../lib/logMgr.php";
include_once dirname(__FILE__)."/../lib/util.php";
include_once dirname(__FILE__)."/../sqlData/User.php";

class cmdLogin extends postHandle
{
	public function handle()
	{
		logMgr::writeLog( "[cmdLogin][handle]" );
		$req = $this->getReq();
		$resp = $this->getResp();
		
		$user = null;
		$sql = User::getSelectSql($req->UserName);
		$result = $this->queryDB( $sql );
		
		// 找到就檢查密碼
		if($result->num_rows > 0)
		{
			$user = User::rebuildUser( $result->fetch_assoc() );
		}
		
		// TEST 找不到暫時先生一個
		else
		{
			$user = $this->createUser($req->UserName,$req->Password);
		}
		$result->free();
		
		// 檢查帳號存在
		if( $user == null)
		{
			$this->setErrorCode(error::$USER_NOT_FOUND);
			$this->writeResp();
			return ;
		}

		// 檢查密碼正確
		if( !$user->checkPassword( $req->Password ) )
		{
			$this->setErrorCode(error::$USER_NOT_FOUND);
			$this->writeResp();
			return ;
		}
		
		$user->setProperty( "login_time", time() );
		$user->setProperty( "update_by", $user->user_name );
		$updateSql = $user->getUpdateSql();
		if( $updateSql )
		{
			$this->queryDB($updateSql);
		}
		
		$resp->UserID = $user->getUserID();
		$resp->GuestKey = $user->guest_key;
		$this->writeResp();
	}
	
	private function createUser($userName, $password)
	{
		$newUser = User::createUser( "email", $userName, "phone", $password, $userName );
		$insertSql = $newUser->getInsertSql();
		
		if( !$this->queryDB( $insertSql ) )
		{
			$resp = $this->getResp();
			// $this->setErrorCode(error::);
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
    public $UserID = "";
    public $GuestKey = "";
	
}


// 只接收post
if( $_SERVER["REQUEST_METHOD"] != "POST" )
{
	return;
}

$login = new cmdLogin();
$login->handle();
?>
