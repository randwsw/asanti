<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

require_once("include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
mysqli_set_charset($conn, "utf8");

$user_key = $conn->real_escape_string($_GET['userkey']);
$user_key = $purifier->purify($user_key);

if(!session_id())
	session_start();

$returnValue = null;

// Get news /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

mysqli_query($conn,"UPDATE users SET conf=1 WHERE id=(SELECT user_id FROM usr_activate WHERE user_key='$user_key');") or die(mysql_error()."update failed");;

mysqli_query($conn,"DELETE FROM usr_activate WHERE user_key='$user_key'") or die(mysql_error()."update failed");

mysqli_close($conn);
 header( 'Location: ../active.php' ) ;
?>

