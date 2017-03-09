<?php
include_once dirname(__FILE__)."/logMgr.php";

class postHandle
{
	private $dbConn = null;
	private $argMap = null;
	public function handle()
	{
		logMgr::writeLog( "please override handle method" );
	}

	public function __construct()
	{
		$this->argMap = array();
		if ( array_key_exists( "arg", $_POST ) ) 
		{
			$tmpMap = json_decode($_POST["arg"], true);
			if( $tmpMap != null ) 
			{
				$this->argMap = $tmpMap;
			} 
		}
		logMgr::writeLog( json_encode($this->argMap) );
		
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
	
	protected function getPostArg( $argName )
	{
		if ( !array_key_exists( $argName, $this->argMap ) )
		{
			return null;
		}
		
		return $this->argMap[$argName];
	}
}

?>
