<?php
if(isset($_COOKIE['rememberme'])) {
require_once 'htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$email = $conn->real_escape_string($_COOKIE['rememberme']);
$email= $purifier->purify($email);

$result = mysqli_query($conn,"SELECT id, email, name, lastName FROM users WHERE email = '$email'");
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
}
mysqli_close($conn);
}
?>