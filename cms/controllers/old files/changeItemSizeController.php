<?php

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$itemId = $_POST['itemId'];
$sizeId = $_POST['sizeId'];
$active = $_POST['active'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if($active == "1"){
	mysqli_query($conn,"INSERT INTO size_item (sizeId, itemId) VALUES ($sizeId, $itemId)");
}else{
	if($active == "0"){
		mysqli_query($conn,"DELETE FROM size_item WHERE itemId = $itemId AND sizeId = $sizeId");
	}
}

mysqli_close($conn);
?>