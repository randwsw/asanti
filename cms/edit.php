<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}
?>

<?php


// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
require_once("../include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);

$itemId = $_POST['passItemId'];
$categoryId = $_POST['categoryToPost'];
$itemName = $_POST['name'];
$itemDescription = $_POST['description'];
$itemPrice = $_POST['price'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	echo("item id: " . $itemId . "   nazwa: " . $itemName . "   cena: " . $itemPrice . "  opis: " . $itemDescription);


	mysqli_query($conn,'UPDATE item SET name = "' . $itemName . '", description = "' . $itemDescription . '", price = "' . $itemPrice . '" WHERE id = "' . $itemId . '"');
	mysqli_query($conn,"UPDATE category_con SET cat_id = $categoryId WHERE item_id = $itemId");



mysqli_close($conn);


?>