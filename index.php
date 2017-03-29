<?php

// 只接收post
if( $_SERVER["REQUEST_METHOD"] != "POST" )
{
	return;
}

binInput();
// jsonInput();

function binInput()
{
	$inputBin = file_get_contents('php://input');

	$byte_array = unpack('C*', $inputBin);
	// var_dump($byte_array);
	// echo "\n";
	// index 從1開始
	$deviceID = ($byte_array[1] << 8) + $byte_array[2];
	// echo "deviceID : ".$deviceID." = (".$byte_array[1]." << 8) + ".$byte_array[2]."\n";

	$cmdID = $byte_array[3];
	// echo "cmdID : ".$cmdID." = ".$byte_array[3]."\n";

	$data = implode(array_map("chr", array_slice ( $byte_array , 3 )));

	echo $data;
	
}

function jsonInput()
{
	include_once dirname(__FILE__)."/../lib/requestMgr.php";

	header("Content-Type: application/json;charset=utf-8");
	requestMgr::getInstance()->handle($_POST["cmdID"]);
}
?>
