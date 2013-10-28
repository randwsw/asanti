<?php
if(isset($_POST["submit"])){

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$itemId = $_POST['passItemId'];

$categoryId = $_POST['categoryToPost'];

$itemName = $conn->real_escape_string($_POST['itemName']);
// $itemName = $purifier->purify($itemName);

$itemDescription = $conn->real_escape_string($_POST['description']);
// $itemDescription = $purifier->purify($itemDescription);

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