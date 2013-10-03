

<?php

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
	// GET ALL PHOTOS ------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$itemId = $_POST['itemId'];
		class photo {
			public $id;
			public $url;
		}
												
		$photoList = array();		
						
		$result3 = mysqli_query($conn,"SELECT * FROM photo WHERE item_id = $itemId AND isHEadPhoto = '0' ORDER BY orderN ASC");
												
		while($row3 = mysqli_fetch_array($result3))
			{
				$photo = new photo;
				$photo->id = $row3['id'];
				$photo->url = $row3['url'];
				array_push($photoList, $photo);
			}
		
		foreach($photoList as $photo){
			echo("<li>
					<div class='frame'><span class='helper'></span>
						<img src='$photo->url' class='photoThumb' id='itemPhoto$photo->id'/>
					</div><div class='moveBefore' id='moveBefore$photo->id'><</div><div class='deletePhoto' id='deletePhoto$photo->id'>X</div><div class='moveAfter' id='moveAfter$photo->id'>></div>
			</li>")	;
		}
		mysqli_close($conn);
		break;
		
		
		
		
		
		case "delete":
		// ---------------------------------------------------------------------------------------------------------- //
		// DELETE PHOTO --------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$photoId = $_POST['photoId'];
			$itemId = $_POST['itemId'];
			$orderN = "";
			$nextOrderN = "";
			$decreasedOrderN = "";
			$nextItem = "";
			$countItems = "";
			
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
			break;
			
			
			
			
			
		case "changeOrder":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE PHOTOS ORDER -------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$photoId = $_POST['photoId'];
			$itemId = $_POST['itemId'];
			$direction = $_POST['direction'];
			
			$orderN = "";
			$before = "";
			$next = "";
			$id2 = "";
			
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
			break;
}
?>