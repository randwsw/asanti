<?php
			
			
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn2=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn3=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn4=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$itemToDelete = $conn->real_escape_string($_POST['itemToDelete']);
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

$pom="";


// CHANGE HEAD PHOTO
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	mysqli_query($conn,"UPDATE item SET headPhotoId='0' WHERE id='$itemToDelete'");



// DELETE PHOTOS

  	
  	$sql="DELETE FROM photo
			WHERE item_id = '$itemToDelete'";
		if (!mysqli_query($conn,$sql))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		// mysqli_close($conn);
	  	}else
	  	{
	  		$pom.="PHOTO DELETED______";
			// mysqli_close($conn);
	  	}
	
	
  
  
// DELETE CATEGORY CONNECTIONS

  	$sql2="DELETE FROM category_con
			WHERE item_id = '$itemToDelete'";
		if (!mysqli_query($conn,$sql2))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		// mysqli_close($conn2);
	  	}else
	  	{
	  		$pom.="CATEGORY DELETED_____";
			// mysqli_close($conn2);
	  	}
	
	

// DELETE SIZE ITEM CONNECTIONS

$sql3="DELETE FROM size_item
			WHERE itemId = '$itemToDelete'";
		if (!mysqli_query($conn,$sql3))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		// mysqli_close($conn3);
	  	}else
	  	{
	  		$pom.="SIZE DELETED_____";
			// mysqli_close($conn3);
	  	}
	
	
	

// DELETE ITEM

  	$sql4="DELETE FROM item
			WHERE id = '$itemToDelete'";
		if (!mysqli_query($conn,$sql4))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		// mysqli_close($conn4);
	  	}else
	  	{
	  		$pom.="ITEM DELETED_____";
			// mysqli_close($conn4);
	  	}
	
	mysqli_close($conn);
	
// DELETE FILES AND DIR ON FTP SERVER

set_time_limit(300);//for uploading big files
	
	$paths="/asanti/img/items/" . $itemToDelete;

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
	$files = ftp_nlist($conn_id, ".");
	foreach ($files as $file)
	{
	    ftp_delete($conn_id, $file);
	}    
		ftp_rmdir($conn_id, $paths);
		// close the FTP connection
		ftp_close($conn_id);	
	$pom.="FILES DELETED_____";
	echo($pom);
?>