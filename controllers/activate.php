<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

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

mysqli_query($conn,"UPDATE users SET conf=1 WHERE id=(SELECT user_id FROM usr_activate WHERE user_key='$user_key');");

mysqli_query($conn,"DELETE FROM usr_activate WHERE user_key='$user_key'");

mysqli_close($conn);
echo("Ok");
?>

