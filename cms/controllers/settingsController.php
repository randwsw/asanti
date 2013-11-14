<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}


// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
require_once("../../include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);

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
		$log = $_SESSION['log'];
		$newPw = $_POST['newPw'];

		mysqli_query($conn,"UPDATE control SET pass='$newPw' WHERE log='$log'");
	
		mysqli_close($conn);
	}
	break;
	
	
	
	
	
	case "changeLog":
	// ---------------------------------------------------------------------------------------------------------- //
	// CHANGE CMS LOGIN ----------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	if($_SESSION['status'] == "adm"){
		$newLog = $_POST['newLog'];

		mysqli_query($conn,"UPDATE control SET log = '$newLog'");
	
		mysqli_close($conn);
	}
	break;
}


?>