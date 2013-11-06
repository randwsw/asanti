<?php
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$itemId1 = $_POST['itemId1'];
$itemId2 = $_POST['itemId2'];
echo ($itemId1 . "   " . $itemId2);
// //////////////////////////////////////////////////////////////////////////////////////////////////// //


// ADD SET
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }



// ENCODING TO UTF8
$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);



$sql="INSERT INTO item_conn (item1_id, item2_id)
VALUES
('$itemId1', '$itemId2')";

if (!mysqli_query($conn,$sql))
  {
	  die('Error: ' . mysqli_error($conn));
	  mysqli_close($conn);
  }else
  {
  	mysqli_close($conn);
  }




?>