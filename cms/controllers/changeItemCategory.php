<?php

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$itemId = $_POST['itemId'];
$sizeId = $_POST['categoryId'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


	mysqli_query($conn,"UPDATE category_con SET cat_id = $categoryId WHERE item_id = $itemId");


mysqli_close($conn);
?>