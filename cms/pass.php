<?php
if(isset($_POST["submit"])){

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
require_once("../include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
										

$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$itemId = $_POST['passItemId'];

$categoryId = $_POST['categoryToPost'];

$itemName = $conn->real_escape_string($_POST['itemName']);
$itemName = $purifier->purify($itemName);
$itemName = stripslashes($itemName);

$itemDescription2 = $conn->real_escape_string($_POST['description']);
// $itemDescription = $purifier->purify($itemDescription);
$itemDescription = str_replace( '\r\n', '<br />', $itemDescription2 ); 
$itemDescription = stripslashes($itemDescription);

$itemPrice = $_POST['price'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	// echo("item id: " . $itemId . "   nazwa: " . $itemName . "   cena: " . $itemPrice . "  opis: " . $itemDescription);


	mysqli_query($conn,'UPDATE item SET name = "' . $itemName . '", description = "' . $itemDescription . '", price = "' . $itemPrice . '" WHERE id = "' . $itemId . '"');
	mysqli_query($conn,"UPDATE category_con SET cat_id = $categoryId WHERE item_id = $itemId");



mysqli_close($conn);

header("Location: editItem.php?itemId=" . $itemId);

}