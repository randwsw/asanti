<?php

if(!session_id()){
	session_start();
} 

include '../include/checkLog.php';

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
// //////////////////////////////////////////////////////////////////////////////////////////////////// //



$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);


if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }


$action = $_POST['action'];






switch ($action) {
	
	case "getAll":
	// ---------------------------------------------------------------------------------------------------------- //
	// GET ALL ITEMS -------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		if(isset($_POST['sets'])){
			if($_POST['sets'] == "sets"){
				$itemId = $_POST['itemId'];
				$sets = " AND i.id != $itemId AND i.id NOT IN (SELECT ic.item2_id FROM item_conn ic WHERE ic.item1_id = $itemId)
												AND i.id NOT IN (SELECT ic.item1_id FROM item_conn ic WHERE ic.item2_id = $itemId)";
			}else{
				$sets = "";
			}
		}else{
			$sets = "";
		}
		
		if(isset($_POST['promoted'])){
			if($_POST['promoted'] == "promoted"){
				$promoted = " AND i.id NOT IN (SELECT r.item_id FROM recommended r)";
			}else{
				$promoted = "";
			}
		}else{
			$promoted = "";
		}
		
		
		if(isset($_POST['id'])){
			$idFilter = " AND i.id = '" . $_POST['id'] . "'";
		}else{$idFilter = "";}
		
		
		
		
		if(isset($_POST['name'])){
			$nameFilter = " AND i.name = '" . $_POST['name'] . "'";
		}else{$nameFilter = "";}
		
		
		
		
		if(isset($_POST['category'])){
			$category = $_POST['category'];
			if($category == null){
				$categoryFilter = "";
			}else{
				$result2 = mysqli_query($conn,"SELECT catLevel FROM category WHERE id = '$category'");
			while($row2 = mysqli_fetch_array($result2))
				{
					
					$c = $row2['catLevel'];
					// echo($c . "</br>");
					if($c == "1"){
						$categoryFilter = " AND c.id IN (SELECT id FROM category WHERE parentId = '$category')";
						
					}else{
						$categoryFilter = " AND c.id = '$category'";
					}
				}	
			}
			
		}else{$categoryFilter = "";}
		
		
		
		if(isset($_POST['sortBy'])){
			$sortBy = $_POST['sortBy'];
			$direction = $_POST['direction'];
			if($sortBy == null || $direction == null){
				$sort = " ORDER BY i.name ASC";
			}else{
				$sort = " ORDER BY $sortBy $direction";
			}
		}else{
			$sort = " ORDER BY i.name ASC";
		}
		
		
				
				$result = mysqli_query($conn,"SELECT i.id AS itemId, i.name AS itemName, i.description AS itemDescription, 
												i.active AS itemActive, ph.url AS headPhotoUrl, i.price AS itemPrice, 
												c.name AS categoryName, c.urlName AS categoryUrlName
											FROM item i, category c, category_con cc, photo ph
											WHERE c.id = cc.cat_id
											AND i.id = cc.item_id"
											. $idFilter . $nameFilter . $categoryFilter . $sets . $promoted .
											" GROUP BY i.id"
											. $sort);
				
				while($e=mysqli_fetch_assoc($result))
		              $output[]=$e;
		           print(json_encode($output));
			mysqli_close($conn);
			break;
			
			
			
		
		
		case "delete":
		// ---------------------------------------------------------------------------------------------------------- //
		// DELETE ITEM ---------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$itemToDelete = $conn->real_escape_string($_POST['itemToDelete']);
		
		$pom="";
		
	
		mysqli_query($conn,"UPDATE item SET headPhotoId='0' WHERE id='$itemToDelete'");
		
		
		
		// DELETE PHOTOS
		
		  	
		$sql="DELETE FROM photo
			WHERE item_id = '$itemToDelete'";
		if (!mysqli_query($conn,$sql))
		{
			  die('Error: ' . mysqli_error($conn));
			  mysqli_close($conn);
		}else
		{
			
		}
			
			
		  
		  
		// DELETE CATEGORY CONNECTIONS
		
		  	$sql2="DELETE FROM category_con
					WHERE item_id = '$itemToDelete'";
				if (!mysqli_query($conn,$sql2))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  		
			  	}
			
			
		
		// DELETE SIZE ITEM CONNECTIONS
		
		$sql3="DELETE FROM size_item
					WHERE itemId = '$itemToDelete'";
				if (!mysqli_query($conn,$sql3))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  		
			  	}
			
		// DELETE RECOMMENDED CONNECTIONS	
		
		$sql5="DELETE FROM recommended
					WHERE item_id = '$itemToDelete'";
				if (!mysqli_query($conn,$sql5))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  		
			  	}
		
		
		// DELETE ITEM CONNECTIONS
			$itemId2 = $itemToDelete;
				
				$result = mysqli_query($conn,"SELECT id FROM item_conn WHERE (item1_id = $itemId2 OR item2_id = $itemId2)");
													
				while($row = mysqli_fetch_array($result))
					{
						$id = $row['id'];
						$sql="DELETE FROM item_conn
						WHERE id = '$id'";
						if (!mysqli_query($conn,$sql))
						{
							  die('Error: ' . mysqli_error($conn));
							  mysqli_close($conn);
						}else
						{
							echo("success");
						}	
					}
		
		
		
		// DELETE ITEM
		
		  	$sql4="DELETE FROM item
					WHERE id = '$itemToDelete'";
				if (!mysqli_query($conn,$sql4))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  		
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
			break;
			
			
			
			
		
		case "changeActive":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE ACTIVE ITEM --------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$itemId = $_POST['itemId'];
			$active = $_POST['active'];
			
			mysqli_query($conn,"UPDATE item SET active=$active WHERE id='$itemId'");
			
			mysqli_close($conn);
			break;
}
?>