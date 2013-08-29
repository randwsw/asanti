<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$email = $conn->real_escape_string($_POST['email']);
$email= $purifier->purify($email);

$password1 = $conn->real_escape_string($_POST['password1']);
$password1= $purifier->purify($password1);

$em = null;

// Get news /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$result = mysqli_query($conn,"SELECT email, name, lastName FROM users WHERE email = '$email' AND password = '$password1'");
		while($e = mysqli_fetch_array($result))
		  {
				$em = $e['email'];
				$name = $e['name'];
				$lastname = $e['lastName'];
		  }
if($em != null)	 
{
	session_start();
	if(isset($_SESSION['login']))
  		unset($_SESSION['login']);
	if(isset($_SESSION['name']))
  		unset($_SESSION['name']);
	if(isset($_SESSION['lastname']))
  		unset($_SESSION['lastname']);
	$_SESSION['login']=$em;
	$_SESSION['name']=$name;
	$_SESSION['lastname']=$lastname;
}
else {
	print("Zły login lub hasło");
}
mysqli_close($conn);
?>
