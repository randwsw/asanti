<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$u = $_SESSION['login'];

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

require_once("include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
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

$result = mysqli_query( $conn,"SELECT * FROM remember_me WHERE user_id =(SELECT id FROM users WHERE email = '$u')" );

if( mysqli_num_rows($result) > 0) {
	$result = mysqli_query($conn,"DELETE FROM remember_me WHERE user_id = (SELECT id FROM users WHERE email = '$u')");
}

if(isset($_SESSION['login']))
  unset($_SESSION['login']);
if(isset($_SESSION['name']))
  unset($_SESSION['name']);
if(isset($_SESSION['lastname']))
  unset($_SESSION['lastname']);

mysqli_close($conn);
?>