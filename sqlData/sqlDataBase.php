<?php
include_once dirname(__FILE__)."/../lib/logMgr.php";
include_once dirname(__FILE__)."/../lib/util.php";

class sqlDataBase
{
	private $updateCol = array();	// 要更新的DB欄位

	public function setProperty( $property, $value )
	{
		// 檢查 property 存在
		if (!property_exists($this, $property)) 
		{
			logMgr::writeLog(util::string_format("[sqlDataBase][setProperty] {0} is not exist\n",$property));
			return;
		}
		
		// 檢查型態正確
		if( gettype($this->{$property}) != gettype($value) )
		{
			logMgr::writeLog(util::string_format("[sqlDataBase][setProperty] {0} is type the same : {1} != {2}\n", $property, gettype($this->{$property}), gettype($value)) );
			return ;
		}
		
		$this->{$property} = $value;
		array_push ( $this->updateCol , $property);
	}
	
	public function getUpdatePropertySql()
	{
		if( !$this->updateCol || (count($this->updateCol)==0) ){return null;}
		
		$size = count($this->updateCol);
		$updatePropertySql = "";
		for( $index = 0;$index < $size;$index++ )
		{
			$property = $this->updateCol[$index];
			$tmpSql = util::string_format("{0} = '{1}' ",$property, $this->{$property});
			if($index == 0)
			{
				$updatePropertySql .= $tmpSql;
			}
			else
			{
				$updatePropertySql .= (",".$tmpSql);
			}
		}
		
		return $updatePropertySql;
	}
	
	// TODO base64 加密
	public function checkPassword( $pwd )
	{
		return ($pwd == $this->password);
	}
}

?>
