<?php


$photoId = $_POST['photoId'];
$itemId = $_POST['itemId'];
$orderN = "";
$nextOrderN = "";
$decreasedOrderN = "";
$nextItem = "";
$countItems = "";

$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
										
										
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }



$result = mysqli_query($conn, "SELECT orderN FROM photo WHERE id = $photoId");

while($row = mysqli_fetch_array($result))
	{
		$orderN = $row['orderN'];
	}	




$result2 = mysqli_query($conn, "SELECT COUNT(*) AS countItems FROM photo WHERE orderN > $orderN AND item_id = $itemId");

while($row2 = mysqli_fetch_array($result2))
	{
		$countItems = $row2['countItems'];
	}	

//DELETE PHOTO
$sql="DELETE FROM photo
		WHERE id = $photoId";
	if (!mysqli_query($conn,$sql))
	{
	 		die('Error: ' . mysqli_error($conn));

  	}else
  	{
	  		
  	}	
	
	


if($countItems > 0){
	
	$nextOrderN = $orderN+1;
	
	for($i = 0; $i<$countItems; $i++){
		
		$result3 = mysqli_query($conn, "SELECT id FROM photo WHERE orderN = $nextOrderN AND item_id = $itemId");
	
		while($row3 = mysqli_fetch_array($result3))
			{
				$nextItem = $row3['id'];
			}	
			
		$decreasedOrderN = $nextOrderN - 1;	
		$nextOrderN++;
		// echo("photo_" . $nextItem . "    don_" . $decreasedOrderN . "    non_" . $nextOrderN . "</br>");
		
		mysqli_query($conn,"UPDATE photo SET orderN = $decreasedOrderN WHERE id=$nextItem");
	}
}
	

	
	
	
	


mysqli_close($conn);
?>