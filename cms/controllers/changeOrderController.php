<?php

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$photoId = $_POST['photoId'];
$itemId = $_POST['itemId'];
$direction = $_POST['direction'];

$orderN = "";
$before = "";
$next = "";
$id2 = "";
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }



$result = mysqli_query($conn, "SELECT orderN FROM photo WHERE id = $photoId");

while($row = mysqli_fetch_array($result))
	{
		$orderN = $row['orderN'];
	}	
	
	
	
$result2 = mysqli_query($conn, "SELECT MAX(orderN) AS maxOrderN FROM photo WHERE item_id = $itemId");

while($row2 = mysqli_fetch_array($result2))
	{
		$maxOrderN = $row2['maxOrderN'];
	}	



if($orderN != "1" && $direction == "left"){
	
	$before = $orderN - 1;
	
	$result3 = mysqli_query($conn, "SELECT id FROM photo WHERE item_id = $itemId AND orderN = $before");

	while($row3 = mysqli_fetch_array($result3))
		{
			$id2 = $row3['id'];
		}	
		
	mysqli_query($conn,"UPDATE photo SET orderN = $orderN WHERE id=$id2");
	mysqli_query($conn,"UPDATE photo SET orderN = $before WHERE id='$photoId'");
}else{
	if($orderN < $maxOrderN && $direction == "right"){
		
		$next = $orderN + 1;
		
		$result4 = mysqli_query($conn, "SELECT id FROM photo WHERE item_id = $itemId AND orderN = $next");

		while($row4 = mysqli_fetch_array($result4))
			{
				$id2 = $row4['id'];
			}	
			
		mysqli_query($conn,"UPDATE photo SET orderN = $orderN WHERE id=$id2");
		mysqli_query($conn,"UPDATE photo SET orderN = $next WHERE id='$photoId'");
	}
}



mysqli_close($conn);
?>