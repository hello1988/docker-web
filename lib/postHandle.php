<?php
include_once dirname(__FILE__)."/logMgr.php";

class postHandle
{
	private $dbConn = null;
	public static function handle()
	{
		logMgr::writeLog( "please override handle method" );
	}
	
	public function __construct() 
	{
		closeConn();
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
}

?>
