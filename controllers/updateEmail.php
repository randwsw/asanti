<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

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