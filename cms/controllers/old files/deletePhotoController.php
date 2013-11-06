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



$result = mysqli_query($conn, "SELECT orderN, name FROM photo WHERE id = $photoId");

while($row = mysqli_fetch_array($result))
	{
		$orderN = $row['orderN'];
		$file = $row['name'];
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





// DELETE FILES AND DIR ON FTP SERVER

				set_time_limit(300);//for uploading big files
				
				$paths="/asanti/img/items/" . $itemId;
			
				$ftp_server="serwer1309748.home.pl";
			
				$ftp_user_name="serwer1309748";
			
				$ftp_user_pass="9!c3Q9";
			
				$filesList = array();
			
			
			
				// set up a connection to ftp server
				$conn_id = ftp_connect($ftp_server);
				
				// login with username and password
				$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
			
				// check connection and login result
				if ((!$conn_id) || (!$login_result)) {
					echo "FTP connection has encountered an error!";
					echo "Attempted to connect to $ftp_server for user $ftp_user_name....";
					exit;
				   	} else {
				       	echo "Connected to $ftp_server, for user $ftp_user_name".".....";
				   	}
			   
			   
				
			
				ftp_chdir($conn_id, $paths);
				// $files = ftp_nlist($conn_id, ".");
				// foreach ($files as $file)
				// {
				    // ftp_delete($conn_id, $file);
				// }
				ftp_delete($conn_id, $file);  
					// ftp_rmdir($conn_id, $paths);
					// close the FTP connection
					ftp_close($conn_id);	
				$pom.="FILES DELETED_____";
				echo($pom);


?>