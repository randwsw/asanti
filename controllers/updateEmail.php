<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

require_once("include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
mysqli_set_charset($conn, "utf8");

$email1 = $conn->real_escape_string($_POST['email1']);
$email1 = $purifier->purify($email1);

$email2 = $conn->real_escape_string($_POST['email2']);
$email2 = $purifier->purify($email2);


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

mysqli_query($conn,"UPDATE users SET email = '$email1' WHERE email='$email';");

if(isset($_SESSION['login']))
{
	unset($_SESSION['login']);
  	$_SESSION['login'] = $email1;
}


mysqli_close($conn);
?>