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
	
	
	case "changeActive":
	// ---------------------------------------------------------------------------------------------------------- //
	// CHANGE ACTIVE -------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	
		$discId = $_POST['discId'];
		$active = $_POST['active'];
		
		mysqli_query($conn,"UPDATE discount SET active=$active WHERE id='$discId'");
	
		mysqli_close($conn);
	break;




	
	case "updateDisc":
	// ---------------------------------------------------------------------------------------------------------- //
	// UPDATE DISCOUNT VALUES ----------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$discId = $_POST['discId'];
		$count = $_POST['count'];
		$disc = $_POST['disc'];
		
		mysqli_query($conn,"UPDATE discount SET items_value = '$count', value = '$disc' WHERE id='$discId'");
	
		mysqli_close($conn);
}


?>