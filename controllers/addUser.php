<?php

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

require_once("include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
mysqli_set_charset($conn, "utf8");

$email = $conn->real_escape_string($_POST['email']);
$email= $purifier->purify($email);

$password1 = $conn->real_escape_string($_POST['password1']);
$password1= $purifier->purify($password1);

$password2 = $conn->real_escape_string($_POST['password2']);
$password2= $purifier->purify($password2);

$name = $conn->real_escape_string($_POST['name']);
$name= $purifier->purify($name);

$lastname = $conn->real_escape_string($_POST['lastname']);
$lastname= $purifier->purify($lastname);

$street = $conn->real_escape_string($_POST['street']);
$street= $purifier->purify($street);

$pcode = $conn->real_escape_string($_POST['pcode']);
$pcode= $purifier->purify($pcode);

$city = $conn->real_escape_string($_POST['city']);
$city= $purifier->purify($city);

$phone = $conn->real_escape_string($_POST['phone']);
$phone= $purifier->purify($phone);



echo("$email $password1 $phone $street $pcode $city");

$returnValue = null;


// Get news /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

mysqli_query($conn,"INSERT INTO users (email, password, name, lastName)
VALUES ('$email', '$password1', '$name', '$lastname')");

$result = mysqli_query($conn,"SELECT id FROM users WHERE email = '$email'");
		while($e = mysqli_fetch_array($result))
		  {
				$returnValue = $e['id'];
		  }

mysqli_query($conn,"INSERT INTO phone (pValue, user_id)
VALUES ('$phone', $returnValue)");

mysqli_query($conn,"INSERT INTO address (pcode, street, city, user_id)
VALUES ('$pcode', '$street', '$city', $returnValue)");

//Aktywacja
$activationKey = md5(time().date("Y:m:d"));

mysqli_query($conn,"INSERT INTO usr_activate (user_id, user_key)
VALUES ($returnValue, '$activationKey')");

$to = "$email";

$from = "no-reply@asanti.com";
$headerFields = array(
    "From: {$from}",
    "MIME-Version: 1.0",
    "Content-Type: text/html;charset=utf-8"
	);
$subject = "Aktywacja konta";
$message = "Kliknij w link, aby aktywować konto: http://www.serwer1309748.home.pl/asanti/controllers/activate.php?userkey=$activationKey";
$headers = "From:" . $from;
mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',$message, implode("\r\n", $headerFields));
//echo "Mail Sent.";
//echo($activationKey);
//echo($message);


mysqli_close($conn);


?>