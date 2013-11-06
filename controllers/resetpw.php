<?php
if(!session_id()) { session_start();} 
 if(isset($_SESSION['login'])){
	header('location: index.php');
}

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
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
$headers = "From:" . $from;
mail($to,$subject,$message,$headers);
echo($message);
?>