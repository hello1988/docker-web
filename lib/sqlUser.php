<?php
include_once dirname(__FILE__)."util.php";

class User
{
	private $User_ID = null;	// varchar(50)
	
	public $email = "";			// varchar(200)
	public $user_name = "";		// varchar(50)
	public $phone = "";			// varchar(30)
	public $password = "";		// text
	public $user_type = 0;		// tinyint(4)
	public $update_by = "";		// varchar(50)
	public $otp = "";			// varchar(50)
	public $login_time = 0;		// bigint(20)
	//					   createUser( "email", $userName, "phone", $password, $userName );
	public static function createUser( $email, $userName, $phone, $password, $createBy )
	{
		$userID = util::createUserID();
		
		$newUser = new User($userID);
		$newUser->email = $email;
		$newUser->user_name = $userName;
		$newUser->phone = $phone;
		$newUser->password = $password;
		$newUser->update_by = $createBy;
		$newUser->otp = util::getOTP( 30 );
		$newUser->login_time = time();
		
		return $newUser;
	}
	
	public static function getSelectSql($userName, $password)
	{
		$selectSql = util::string_format("select * from User where user_name = '{0}' and password = '{1}' limit 1;", $userName, $password);
		// echo "getSelectSql : ".$selectSql."\n";
		return $selectSql;
		
	}
	
	public function __construct( $userID )
	{
		$this->User_ID = $userID;
	}
	
	public function getUserID()
	{
		return $User_ID;
	}
	
	public function getInsertSql()
	{
		$insertSql = "insert into User (User_ID, user_name, phone, password, update_by, otp, login_time ) values('{0}', '{1}', '{2}', '{3}', '{4}', '{5}', {6} );";
		$insertSql = util::string_format($insertSql, $this->User_ID, $this->user_name, $this->phone, $this->password, $this->update_by, $this->otp, $this->login_time);
		// echo "getInsertSql : ".$insertSql."\n";
		return $insertSql;
	}
}

?>
