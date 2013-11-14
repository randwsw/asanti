<?php
if(!session_id()) { session_start();} 
 if(isset($_SESSION['login'])){
	header('location: index.php');
}

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

require_once("include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
mysqli_set_charset($conn, "utf8");

$email = $conn->real_escape_string($_POST['email']);
$email= $purifier->purify($email);


$activationKey = md5(time().date("Y:m:d"));
$newpw = substr($activationKey , 0, 12);

mysqli_query($conn,"UPDATE users SET password='$newpw' WHERE email='$email';") or die(mysql_error()."update failed");;


$to = "$email";
$subject = "Reset hasła";
$message = "Hasło dla tego adresu email zostało zresetowane. Nowe hasło: '".$newpw."'. Zmiany hasła można dokonać w profilu użytkownika.";
$from = "no-reply@asanti.com";
$headerFields = array(
    "From: {$from}",
    "MIME-Version: 1.0",
    "Content-Type: text/html;charset=utf-8"
	);
$headers = "From:" . $from;
mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',$message, implode("\r\n", $headerFields));

?>