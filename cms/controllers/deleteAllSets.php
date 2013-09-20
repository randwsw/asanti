<?php

		
			
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$itemId2 = $_POST['itemId2'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// 
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }


// ENCODING TO UTF8
$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);	

$result = mysqli_query($conn,"SELECT COUNT(*) AS count FROM item_conn WHERE item1_id = '$itemId2' OR item2_id = '$itemId2'");
										
while($row1 = mysqli_fetch_array($result))
	{
		$count = $row1['count'];
	}
	
// DELETE CATEGORY
  	for($i=0; $i<$count; $i++){
  		$sql="DELETE FROM item_conn
				WHERE (item2_id = '$itemId2') OR (item1_id = '$itemId2')";
		if (!mysqli_query($conn,$sql))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		mysqli_close($conn);
	  	}else
	  	{

	  	}
		mysqli_close($conn);
  	}
	
  		

  	
	
	
  
  

?>




