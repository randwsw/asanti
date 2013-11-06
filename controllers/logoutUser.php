<?php
if(!session_id())
	{
	    session_start();
	}
$u = $_SESSION['login'];

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
mysqli_set_charset($conn, "utf8");

$result = mysqli_query( $conn,"SELECT * FROM remember_me WHERE user_id =(SELECT id FROM users WHERE email = '$u')" );

if( mysqli_num_rows($result) > 0) {
	$result = mysqli_query($conn,"DELETE FROM remember_me WHERE user_id = (SELECT id FROM users WHERE email = '$u')");
}
if(isset($_SESSION['login']))
  unset($_SESSION['login']);
if(isset($_SESSION['name']))
  unset($_SESSION['name']);
if(isset($_SESSION['lastname']))
  unset($_SESSION['lastname']);
?>