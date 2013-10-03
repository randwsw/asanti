<?php

		
			
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$categoryId = $_POST['categoryId'];
$count = "";
$count2 = "";
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// 
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }


// ENCODING TO UTF8
$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);	

	
	
	$result = mysqli_query($conn,"SELECT COUNT(*) AS catCount FROM category_con WHERE cat_id = '$categoryId'");
		
		while($row = mysqli_fetch_array($result))
			{
				$count = $row['catCount'];
			}

	$result2 = mysqli_query($conn,"SELECT COUNT(*) AS catCount2 FROM category WHERE parentId = '$categoryId'");
			
		while($row2 = mysqli_fetch_array($result2))
			{
				$count2 = $row2['catCount2'];
			}
			
			
// DELETE CATEGORY
  	if($count != 0 || $count2 != 0){
  		echo("failed");
  	}else{
  		$sql="DELETE FROM category
				WHERE id = '$categoryId'";
		if (!mysqli_query($conn,$sql))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		mysqli_close($conn);
	  	}else
	  	{
	  		echo("success");
			mysqli_close($conn);
	  	}
  	}
  	
	
	
  
  

?>




