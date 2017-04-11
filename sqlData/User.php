<?php
include_once dirname(__FILE__)."/sqlDataBase.php";
include_once dirname(__FILE__)."/../lib/logMgr.php";
include_once dirname(__FILE__)."/../lib/util.php";

class User extends sqlDataBase
{
	private $User_ID = null;	// varchar(50)
	private $updateCol = null;	// 要更新的DB欄位
	
	public $email = "";			// varchar(200)
	public $user_name = "";		// varchar(50)
	public $phone = "";			// varchar(30)
	public $password = "";		// text
	public $user_type = 0;		// tinyint(4)
	public $update_by = "";		// varchar(50)
	public $guest_key = "";		// varchar(50)
	public $login_time = 0;		// bigint(20)

	public static function createUser( $email, $userName, $phone, $password, $createBy )
	{
		$userID = util::createUserID();
		
		$newUser = new User($userID);
		$newUser->email = $email;
		$newUser->user_name = $userName;
		$newUser->phone = $phone;
		$newUser->password = $password;
		$newUser->update_by = $createBy;
		$newUser->guest_key = util::getGuestKey();
		$newUser->login_time = time();
		
		return $newUser;
	}
	
	public static function rebuildUser( $dbRowData )
	{
		// echo "rebuildUser : ".gettype($dbRowData)."\n";
		if( !$dbRowData || !is_array($dbRowData) )
		{
			return null;
		}
		$userID = $dbRowData["User_ID"];
		
		$user = new User($userID);
		$user->email = $dbRowData["email"];
		$user->user_name = $dbRowData["user_name"];
		$user->phone = $dbRowData["phone"];
		$user->password = $dbRowData["password"];
		$user->user_type = intval( $dbRowData["user_type"] );
		$user->update_by = $dbRowData["update_by"];
		$user->guest_key = $dbRowData["guest_key"];
		$user->login_time = intval( $dbRowData["login_time"] );

		return $user;
	}
	
	public static function getSelectSql($userName)
	{
		$selectSql = util::string_format("select * from User where user_name = '{0}' limit 1;", $userName);
		// echo "getSelectSql : ".$selectSql."\n";
		return $selectSql;
		
	}
	
	public function __construct( $userID )
	{
		$this->User_ID = $userID;
		$this->updateCol = array();
	}
	
	public function getUserID()
	{
		return $this->User_ID;
	}
	
	public function getInsertSql()
	{
		$insertSql = "insert into User (User_ID, user_name, phone, password, update_by, guest_key, login_time ) values('{0}', '{1}', '{2}', '{3}', '{4}', '{5}', {6} );";
		$insertSql = util::string_format($insertSql, $this->User_ID, $this->user_name, $this->phone, $this->password, $this->update_by, $this->guest_key, $this->login_time);
		// echo "getInsertSql : ".$insertSql."\n";
		return $insertSql;
	}
	
	public function getUpdateSql()
	{
		$propertySql = $this->getUpdatePropertySql();
		if(!$propertySql){return null;}
		
		$updateSql = util::string_format("update User set {0} where User_ID = '{1}';", $propertySql, $this->User_ID);
		return $updateSql;
	}
	
	// TODO base64 加密
	public function checkPassword( $pwd )
	{
		return ($pwd == $this->password);
	}
}

?>
