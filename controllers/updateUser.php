<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

require_once("include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
mysqli_set_charset($conn, "utf8");

$name = $conn->real_escape_string($_POST['name']);
$name= $purifier->purify($name);

$lastname = $conn->real_escape_string($_POST['lastname']);
$lastname= $purifier->purify($lastname);

$street = $conn->real_escape_string($_POST['street']);
$street= $purifier->purify($street);

$pcode = $conn->real_escape_string($_POST['pcode']);
$pcode= $purifier->purify($pcode);

$city = $conn->real_escape_string($_POST['city']);
$city= $purifier->purify($city);

$phone = $conn->real_escape_string($_POST['phone']);
$phone= $purifier->purify($phone);

if(!session_id())
	session_start();
	
$email = $conn->real_escape_string($_SESSION['login']);
$email= $purifier->purify($email);

$returnValue = null;


// Get news /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

mysqli_query($conn,"UPDATE users SET name='$name', lastname='$lastname' WHERE email = '$email'");


$result = mysqli_query($conn,"SELECT id FROM users WHERE email = '$email'");
		while($e = mysqli_fetch_array($result))
		  {
				$returnValue = $e['id'];
		  }

mysqli_query($conn,"UPDATE phone SET pValue='$phone' WHERE user_id = '$returnValue'");

mysqli_query($conn,"UPDATE address SET pcode='$pcode', street='$street', city='$city' WHERE user_id = '$returnValue'");
if(isset($_SESSION['name']))
  $_SESSION['name']= $name;
if(isset($_SESSION['lastname']))
  $_SESSION['lastname']= $lastname;

mysqli_close($conn);
?>