<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
mysqli_set_charset($conn, "utf8");

$pass1 = $conn->real_escape_string($_POST['pass1']);
$pass1 = $purifier->purify($pass1);

$pass2 = $conn->real_escape_string($_POST['pass2']);
$pass2 = $purifier->purify($pass2);


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

mysqli_query($conn,"UPDATE users SET password = '$pass1' WHERE email='$email';");

if(isset($_SESSION['login']))
  unset($_SESSION['login']);
if(isset($_SESSION['name']))
  unset($_SESSION['name']);
if(isset($_SESSION['lastname']))
  unset($_SESSION['lastname']);

mysqli_close($conn);
?>