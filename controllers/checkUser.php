<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
mysqli_set_charset($conn, "utf8");

$email = $conn->real_escape_string($_POST['email']);
$email= $purifier->purify($email);


$returnValue = null;


// Get news /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}



$result = mysqli_query($conn,"SELECT * FROM users WHERE email = '$email'");
		while($e = mysqli_fetch_array($result))
		  {
				$returnValue = $e['email'];
		  }
print($returnValue);
mysqli_close($conn);
?>
