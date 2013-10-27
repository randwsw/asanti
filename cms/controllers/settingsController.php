<?php

if(!session_id()){
	session_start();
} 

include '../include/checkLog.php';

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

require_once '../../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

// //////////////////////////////////////////////////////////////////////////////////////////////////// //



$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);


if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }


$action = $_POST['action'];

switch ($action) {
	
	case "changePass":
	// ---------------------------------------------------------------------------------------------------------- //
	// CHANGE CMS PASSWORD -------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	if($_SESSION['status'] == "adm"){
		$log = $_SESSION['login'];
		$newPw = $_POST['newPw'];

		mysqli_query($conn,"UPDATE control SET pass='$newPw' WHERE log='$log'");
	
		mysqli_close($conn);
	}
	break;
}


?>