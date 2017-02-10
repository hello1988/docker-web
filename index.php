<?php
/*
echo "connect to mysql</br>";
$servername = getenv("MYSQL_PORT_3306_TCP_ADDR");
$username = "root";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully</br>";


$sql = "use items;";
$conn->query($sql);

$sql = "INSERT INTO tb (main,data) VALUES(16,'qqq');";
$conn->query($sql);

$sql = "UPDATE tb SET data = 'ddd' WHERE main = 4;";
$conn->query($sql);

$sql = "select * from items.tb;";
$result = $conn->query($sql);
if ($result->num_rows > 0) 
{
    // output data of each row
    while($row = $result->fetch_assoc()) 
	{
        echo "id: " . $row["main"]."</br>";
        echo "data: " . $row["data"]."</br>";
    }
} 
else 
{
    echo "0 results</br>";
}
mysqli_close($conn);
*/

echo $_POST["data"];
?>
