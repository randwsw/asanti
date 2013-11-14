<?php
if(!session_id())
	{
	    session_start();
	}
$u = $_SESSION['login'];

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

require_once("include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
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