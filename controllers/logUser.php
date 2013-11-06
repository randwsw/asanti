<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
mysqli_set_charset($conn, "utf8");

$email = $conn->real_escape_string($_POST['email']);
$email= $purifier->purify($email);

$rememberMe = $conn->real_escape_string($_POST['rememberMe']);
$rememberMe= $purifier->purify($rememberMe);

$password1 = $conn->real_escape_string($_POST['password1']);
$password1= $purifier->purify($password1);

$em = null;

// Get news /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$count=0;
if($rememberMe == "true")
{
	$result = mysqli_query($conn,"SELECT user_id FROM remember_me rm, users u WHERE rm.user_id = u.id AND u.email='$email'");
		if(mysqli_num_rows($result)>0) {
			$count = 1;
		}
}

$result = mysqli_query($conn,"SELECT id, email, name, lastName FROM users WHERE email = '$email' AND password = '$password1' AND conf = 1");
		while($e = mysqli_fetch_array($result))
		  {
				$em = $e['email'];
				$name = $e['name'];
				$lastname = $e['lastName'];
				$id = $e['id'];
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
	
	if($count == 0)
	{
		mysqli_query($conn,"INSERT INTO remember_me (user_id) VALUES ($id)");	
	}
	
}
else {
	print("Zły login, hasło lub nieaktywne konto");
}
mysqli_close($conn);
?>
