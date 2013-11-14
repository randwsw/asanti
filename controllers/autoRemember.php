<?php
if(isset($_COOKIE['rememberme'])) {
require_once 'htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

require_once("include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
mysqli_set_charset($conn, "utf8");

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