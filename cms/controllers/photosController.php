<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}


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
			
			
			
		case "pickHead":
		// ---------------------------------------------------------------------------------------------------------- //
		// PICK HEAD PHOTO ------------------------------------------------------------------------------------------ //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			    $targ_w = 300;
			    $targ_h = 409;
			    $jpeg_quality = 90;
			  
			  	$id = $_POST['passItemId'];
			    $src = $_POST['imgUrl'];
			    $img_r = imagecreatefromjpeg($src);
			    $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
			  
			    imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			    $targ_w,$targ_h,$_POST['w'],$_POST['h']);
			  
			    // header('Content-type: image/jpeg');
			    // imagejpeg($dst_r,'headPhoto' . $id . '.jpg',$jpeg_quality);
			    ob_start();
			    imagejpeg($dst_r);
			   	header("Location: ../editItem.php?itemId=" . $id);
				
				 $i = ob_get_clean(); 
				
			
			
			// CHANGE HEAD PHOTO
				if (mysqli_connect_errno())
				  {
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				  }
				mysqli_query($conn,"UPDATE item SET headPhotoId='0' WHERE id='$id'");
			
			
			
			// DELETE PHOTO
			  	$sql="DELETE FROM photo
						WHERE item_id = '$id' AND isHeadPhoto = '1'";
					if (!mysqli_query($conn,$sql))
					{
				  		die('Error: ' . mysqli_error($conn));
				  		// mysqli_close($conn);
				  	}else
				  	{
						// mysqli_close($conn);
				  	}
			
			
			
			
				
				
				
				set_time_limit(300);//for uploading big files
				
				$file="headPhoto" . $id . ".jpg";
				
				$paths="asanti/img/items/" . $id . "/head";
			
				$ftp_server="serwer1309748.home.pl";
			
				$ftp_user_name="serwer1309748";
			
				$ftp_user_pass="9!c3Q9";
			
			
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
				
				
				   
				if(!ftp_mkdir($conn_id, $paths)){
					echo('DIR EXISTS </br>');
				}else{
					echo('DIR CREATED </br>');
				}
			
			
			
				// Allows overwriting of existing files on the remote FTP server
				$stream_options = array('ftp' => array('overwrite' => true));
				
				// Creates a stream context resource with the defined options
				$stream_context = stream_context_create($stream_options);
			
			
			
			
				// $fp = fopen($file, 'r+');
				$fp = fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . "/" . $paths . "/" . $file, "w", 0, $stream_context);
				fwrite($fp, $i);
				// rewind($fp);       
				// ftp_fput($conn_id, $paths . "/" . $file, $fp, FTP_BINARY);
				// unlink($file);
				// ftp_put($conn_id, $paths.'/'.$name, $filep, FTP_BINARY);
				
				
				// check the upload status
				if (!$fp) {
					// $fp2 = fopen($file, 'a+');
					// $fp2 = fopen("ftp://serwer1309748:9!c3Q9@serwer1309748.home.pl/asanti/img/" . $file, "a+");
					// fwrite($fp2, $i);
					// rewind($fp2);       
					// ftp_fput($conn_id, $paths . "/" . $file, $fp2, FTP_BINARY);
					// unlink($file);
					// if(!$fp2){
						
					// }
				   } else {
				       	echo "Uploaded file to $ftp_server </br>";
				   }
				
				// close the FTP connection
				ftp_close($conn_id);	
				
				
				// Add files to SQL ///////////////////////////////////////////////////////////////////
				 
				 
					$sql1 = ("INSERT INTO photo (item_id, url, isHeadPhoto)
						 VALUES ('" . $id . "', 'http://serwer1309748.home.pl/asanti/img/items/" . $id . "/head/" . $file . "', '1')");
			
						
					if (!mysqli_query($conn,$sql1))
				  	 {
				  		 die('Error: ' . mysqli_error($conn));
				  	 } else {
				  		 echo "INSERTED </br>";
				  	 }	
						
						
						
						
						
					$sql2 = ("UPDATE item SET headPhotoId=(SELECT id FROM photo WHERE isHeadPhoto = 1 AND item_id = '$id') WHERE id='$id'");
			
			
					 if (!mysqli_query($conn,$sql2))
				  	 {
				  		 die('Error: ' . mysqli_error($conn));
				  	 } else {
				  		 echo "UPDATED </br>";
				  	 }
			 	
			 	
			 	
			 	
				 mysqli_close($conn);
			
				 
				
			    exit;

			break;
			
			
			
			
			
		case "addMore":
		// ---------------------------------------------------------------------------------------------------------- //
		// ADD MORE PHOTOS ------------------------------------------------------------------------------------------ //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$itemId = $_POST["passItemId"];
			$lastOrderN = "";
				
				set_time_limit(300);//for uploading big files
				
				$paths="asanti/img/items/" . $itemId;
			
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
			   
			   
				ftp_mkdir($conn_id, $paths);
			
				for($i=0; $i<count($_FILES['userfile']['name']); $i++){
					
					$filep=$_FILES['userfile']['tmp_name'][$i];
					$name=$_FILES['userfile']['name'][$i];	
					
					$upload = ftp_put($conn_id, $paths.'/'.$name, $filep, FTP_BINARY);
				
					// check the upload status
					if (!$upload) {
						echo "FTP upload has encountered an error!";
					   } else {
					   		array_push($filesList, $name);
					       	echo "Uploaded file with name $name to $ftp_server </br>";
					   }
				}
				// close the FTP connection
				ftp_close($conn_id);	
				
				
				// Add files to SQL ///////////////////////////////////////////////////////////////////
				
				
				if (mysqli_connect_errno())
				{
			 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
				
				
				$result = mysqli_query($conn,"SELECT MAX(orderN) AS orderN FROM photo WHERE item_id = $itemId AND isHEadPhoto = '0'");
													
				while($row = mysqli_fetch_array($result))
					{
						$lastOrderN = $row["orderN"];
					}
				
				// TO FIX!!!!!!!!!!!!!!!! ////////////////////////////////////////
				/////////////////////////////////////////////////////////////////
				///////////////////////////////////////////////////////////////
				
				$i=$lastOrderN + 1;
			
				foreach($filesList as $file){
					$sql = ("INSERT INTO photo (name, item_id, url, orderN) 
						VALUES ('" .  $file . "', '" . $itemId . "', 'http://serwer1309748.home.pl/asanti/img/items/" . $itemId	 . "/" . $file . "', '$i')");
						$i++;
				if (!mysqli_query($conn,$sql))
			  	{
			  		die('Error: ' . mysqli_error($conn));
			  	} else {
			  		// echo $i . " record added </br>";
					// $i++;
			  	}
				
				}
				
				header("Location: ../editItem.php?itemId=" . $itemId);
			break;
}
?>