<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}


// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
require_once("../../include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);

require_once '../../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
// //////////////////////////////////////////////////////////////////////////////////////////////////// //



$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);


if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }


$action = $_POST['action'];







switch ($action) {
	
	
	
	
    case "getGalleries":
	// ---------------------------------------------------------------------------------------------------------- //
	// GET ALL GALLERIES ---------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //	
    	if(isset($_POST['sortBy'])){
			$sortBy = $_POST['sortBy'];
			$direction = $_POST['direction'];
			if($sortBy == null || $direction == null){
				$sort = " ORDER BY title ASC";
			}else{
				$sort = " ORDER BY $sortBy $direction";
			}
		}else{
			$sort = " ORDER BY title ASC";
		}		
		
		$result = mysqli_query($conn,"SELECT g.id AS id, g.title AS title, gp.url AS headPhoto FROM gallery g, gallery_photos gp 
									WHERE gp.gallery_id = g.id AND orderN = 1
									GROUP BY g.id"
									 . $sort);
			
			while($e=mysqli_fetch_assoc($result))
	              $output[]=$e;
	           print(json_encode($output));
		mysqli_close($conn);
		break;
    
    
    
	
	
    case "changeActive":
	// ---------------------------------------------------------------------------------------------------------- //
	// CHANGE ACTIVE -------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //	
        $userId = $_POST['userId'];
		$active = $_POST['active'];
		
		mysqli_query($conn,"UPDATE users SET active=$active WHERE id='$userId'");
	
		mysqli_close($conn);
        break;
        
        
		
		
		
    case "add":
	// ---------------------------------------------------------------------------------------------------------- //
	// CREATE NEW GALLERY --------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
        $title = $conn->real_escape_string($_POST['name']);
		$title = $purifier->purify($title);

	
		$sql="INSERT INTO gallery (title)
		VALUES
		('$title')";
		
		if (!mysqli_query($conn,$sql))
		  {
			  die('Error: ' . mysqli_error($conn));
			  mysqli_close($conn);
		  }else
		  {
		  	// mysqli_close($conn);
			if (mysqli_connect_errno())
				  {
				  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
				  }
				
				$result = mysqli_query($conn,"SELECT id FROM gallery ORDER BY id DESC LIMIT 1 ");
				while($row2 = mysqli_fetch_array($result))
				  {
						$lastId = $row2['id'];
				  }
				  
			mysqli_close($conn);
			
		  }
	  
	  
	
		
		set_time_limit(300);//for uploading big files
		
		$paths="asanti/img/gallery/" . $lastId;
	
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
	   
	   	$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
		$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
		
		ftp_mkdir($conn_id, $paths);
	
		for($i=0; $i<count($_FILES['userfile']['name']); $i++){
			
			$filep2=$_FILES['userfile']['tmp_name'][$i];
			$filep =  str_replace($aWhat, $aOn, $filep2);
					
			$name2=$_FILES['userfile']['name'][$i];	
			$name =  str_replace($aWhat, $aOn, $name2);
			
			array_push($filesList, $name);
			
			$upload = ftp_put($conn_id, $paths.'/'.$name, $filep, FTP_BINARY);
			
			chdir("../../img/gallery/" . $lastId . "/");
			foreach(glob($name) as $filename) {
				echo $filename . "</br>";
							    
				list($width, $height) = getimagesize($filename);
				echo("Current width: " . $width . "  Current height: " . $height . "</br>");
								
				$maxWidth = 900;
				$maxHeight = 900;
								
				if($width > $maxWidth){
					if($width > $height){
						$currentAspect = $height/$width;
						$newwidth = $maxWidth;
						$newheight = $newwidth * $currentAspect;
					}
					if($width < $height){
						$currentAspect = $width/$height;
						$newheight = $maxHeight;
						$newwidth = $newheight * $currentAspect;
					}
					if($width == $height){
						$newwidth = $maxWidth;
						$newheight = $maxHeight;
					}
				}else{
									
				if($height > $maxHeight){
					if($width > $height){
						$currentAspect = $height/$width;
						$newwidth = $maxWidth;
						$newheight = $newwidth * $currentAspect;
					}
					if($width < $height){
						$currentAspect = $width/$height;
						$newheight = $maxHeight;
						$newwidth = $newheight * $currentAspect;
					}
					if($width == $height){
						$newwidth = $maxWidth;
						$newheight = $maxHeight;
					}
				}	
				else{
									
				if($width <= $maxWidth && $height <= $maxHeight){
					$newwidth = $width;
					$newheight = $height;
				}
				}
				}
								
				echo "Current aspect ratio: " . $currentAspect . "</br>";
				echo "New width: " . $newwidth . "   New height: " . $newheight . "</br></br>";
								
								
				// Load
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				$source = imagecreatefromjpeg($filename);
				echo($filename);
								
				// Resize
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
								
				// Output
				ob_start();
				imagejpeg($thumb);
				$img = ob_get_clean(); 
								
				// Allows overwriting of existing files on the remote FTP server
				$stream_options = array('ftp' => array('overwrite' => true));
								
				// Creates a stream context resource with the defined options
				$stream_context = stream_context_create($stream_options);
							
							
				$fp = fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . "/" . $paths . "/" . $filename, "w", 0, $stream_context);
				fwrite($fp, $img);						
			}			
			
			
			
			
			
			// check the upload status
			// if (!$upload) {
				// echo "FTP upload has encountered an error!";
			   // } else {
			   	// $name = $purifier->purify($name);
			   		// array_push($filesList, $name);
			       	// echo "Uploaded file with name $name to $ftp_server </br>";
			   // }
		}
		// close the FTP connection
		ftp_close($conn_id);	
		
		
		// Add files to SQL ///////////////////////////////////////////////////////////////////
		
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		
		if (mysqli_connect_errno())
		{
	 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$i="1";
		foreach($filesList as $file){
			$sql = ("INSERT INTO gallery_photos (name, gallery_id, url, orderN) 
				VALUES ('" . $file . "', '" . $lastId . "', 'http://serwer1309748.home.pl/asanti/img/gallery/" . $lastId . "/" . $file . "', '$i')");
				$i++;
		if (!mysqli_query($conn,$sql))
	  	{
	  		die('Error: ' . mysqli_error($conn));
	  	} else {
	  		// echo $i . " record added </br>";
			// $i++;
	  	}
		
		}
		
		header("Location: ../gallery.php");
        break;





	case "delete":
	// ---------------------------------------------------------------------------------------------------------- //
	// DELETE GALLERY ------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //


	
		$galleryToDelete = $conn->real_escape_string($_POST['galleryToDelete']);
		
		
		$pom="";
		
		
		// CHANGE HEAD PHOTO
			if (mysqli_connect_errno())
			  {
			  echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  }
		
		
		
		// DELETE PHOTOS
		
		  	
		  	$sql="DELETE FROM gallery_photos
					WHERE gallery_id = '$galleryToDelete'";
				if (!mysqli_query($conn,$sql))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		// mysqli_close($conn);
			  	}else
			  	{
			  		$pom.="PHOTO DELETED______";
					// mysqli_close($conn);
			  	}
			
			
		  
		  
		
		// DELETE GALLERY
		
		  	$sql4="DELETE FROM gallery
					WHERE id = '$galleryToDelete'";
				if (!mysqli_query($conn,$sql4))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		// mysqli_close($conn4);
			  	}else
			  	{
			  		$pom.="GALLERY DELETED_____";
					// mysqli_close($conn4);
			  	}
			
			mysqli_close($conn);
			
		// DELETE FILES AND DIR ON FTP SERVER
		
		set_time_limit(300);//for uploading big files
			
			$paths="/asanti/img/gallery/" . $galleryToDelete;
		
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

			
			
			
		
		case "changeTitle":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE GALLERY TITLE ------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$id = $conn->real_escape_string($_POST['galleryId']);
			
			$title = $_POST['name'];
			$title = $purifier->purify($title);
			
			mysqli_query($conn,"UPDATE gallery SET title='$title' WHERE id='$id'");
	
			mysqli_close($conn);
			
			header("Location: ../gallery.php");
		break;
		
		
		
		
			
		case "move":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE PHOTOS ORDER -------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$photoId = $_POST['photoId'];
			$id = $_POST['id'];
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
			
			
			
			$result = mysqli_query($conn, "SELECT orderN FROM gallery_photos WHERE id = $photoId");
		
			while($row = mysqli_fetch_array($result))
				{
					$orderN = $row['orderN'];
				}	
				
				
				
			$result2 = mysqli_query($conn, "SELECT MAX(orderN) AS maxOrderN FROM gallery_photos WHERE gallery_id = $id");
			
			while($row2 = mysqli_fetch_array($result2))
				{
					$maxOrderN = $row2['maxOrderN'];
				}	
			
			
			
			if($orderN != "1" && $direction == "left"){
				
				$before = $orderN - 1;
				
				$result3 = mysqli_query($conn, "SELECT id FROM gallery_photos WHERE gallery_id = $id AND orderN = $before");
			
				while($row3 = mysqli_fetch_array($result3))
					{
						$id2 = $row3['id'];
					}	
					
				mysqli_query($conn,"UPDATE gallery_photos SET orderN = $orderN WHERE id=$id2");
				mysqli_query($conn,"UPDATE gallery_photos SET orderN = $before WHERE id='$photoId'");
			}else{
				if($orderN < $maxOrderN && $direction == "right"){
					
					$next = $orderN + 1;
					
					$result4 = mysqli_query($conn, "SELECT id FROM gallery_photos WHERE gallery_id = $id AND orderN = $next");
			
					while($row4 = mysqli_fetch_array($result4))
						{
							$id2 = $row4['id'];
						}	
						
					mysqli_query($conn,"UPDATE gallery_photos SET orderN = $orderN WHERE id=$id2");
					mysqli_query($conn,"UPDATE gallery_photos SET orderN = $next WHERE id='$photoId'");
				}
			}
			
			
			
			mysqli_close($conn);	
			break;
			
			
			
			
			
			case "deletePhoto":
			// ---------------------------------------------------------------------------------------------------------- //
			// DELETE PHOTO --------------------------------------------------------------------------------------------- //
			// ---------------------------------------------------------------------------------------------------------- //
			// ---------------------------------------------------------------------------------------------------------- //
			// ---------------------------------------------------------------------------------------------------------- //
		
				$photoId = $_POST['photoId'];
				$id = $_POST['id'];
				$orderN = "";
				$nextOrderN = "";
				$decreasedOrderN = "";
				$nextItem = "";
				$countItems = "";
				
				
				$result = mysqli_query($conn, "SELECT orderN, name FROM gallery_photos WHERE id = $photoId");
				
				while($row = mysqli_fetch_array($result))
					{
						$orderN = $row['orderN'];
						$file = $row['name'];
					}	
				
				
				
				
				$result2 = mysqli_query($conn, "SELECT COUNT(*) AS countPhotos FROM gallery_photos WHERE orderN > $orderN AND gallery_id = $id");
				
				while($row2 = mysqli_fetch_array($result2))
					{
						$countPhotos = $row2['countPhotos'];
					}	
				
				//DELETE PHOTO
				$sql="DELETE FROM gallery_photos
						WHERE id = $photoId";
					if (!mysqli_query($conn,$sql))
					{
					 		die('Error: ' . mysqli_error($conn));
				
				  	}else
				  	{
					  		
				  	}	
					
					
				
				
				if($countPhotos > 0){
					
					$nextOrderN = $orderN+1;
					
					for($i = 0; $i<$countPhotos; $i++){
						
						$result3 = mysqli_query($conn, "SELECT id FROM gallery_photos WHERE orderN = $nextOrderN AND gallery_id = $id");
					
						while($row3 = mysqli_fetch_array($result3))
							{
								$nextPhoto = $row3['id'];
							}	
							
						$decreasedOrderN = $nextOrderN - 1;	
						$nextOrderN++;
						// echo("photo_" . $nextItem . "    don_" . $decreasedOrderN . "    non_" . $nextOrderN . "</br>");
						
						mysqli_query($conn,"UPDATE gallery_photos SET orderN = $decreasedOrderN WHERE id=$nextPhoto");
					}
				}
			
				mysqli_close($conn);
				
				
				

				// DELETE FILES AND DIR ON FTP SERVER

				set_time_limit(300);//for uploading big files
				
				$paths="/asanti/img/gallery/" . $id;
			
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
				
				
				
				
				
				case "getPhotos":
				// ---------------------------------------------------------------------------------------------------------- //
				// GET PHOTOS ----------------------------------------------------------------------------------------------- //
				// ---------------------------------------------------------------------------------------------------------- //
				// ---------------------------------------------------------------------------------------------------------- //
				// ---------------------------------------------------------------------------------------------------------- //
				
					$id = $_POST['id'];
					$result2 = mysqli_query($conn,"SELECT gp.id AS pId, gp.url AS url FROM gallery_photos gp 
													WHERE gallery_id = $id GROUP BY gp.id ORDER BY gp.orderN");
									
					while($row2 = mysqli_fetch_array($result2))
					{
						$pId = $row2['pId'];
						$url = $row2['url'];
												
						echo("<li>
								<div class='frame'><span class='helper'></span>
									<img src='$url' class='photoThumb' id='itemPhoto$pId'/>
								</div><div class='moveBefore' id='moveBefore$pId'><</div><div class='deletePhoto' id='deletePhoto$pId'>X</div><div class='moveAfter' id='moveAfter$pId'>></div>
							</li>")	;
					}
																
																
																
					mysqli_close($conn);
					break;





				case "addPhotos":
				// ---------------------------------------------------------------------------------------------------------- //
				// ADD MORE PHOTOS ------------------------------------------------------------------------------------------ //
				// ---------------------------------------------------------------------------------------------------------- //
				// ---------------------------------------------------------------------------------------------------------- //
				// ---------------------------------------------------------------------------------------------------------- //
			        $name = $conn->real_escape_string($_POST['name']);
					$name = $purifier->purify($name);
					
					$id = $_POST['galleryId'];
				
					$lastOrderN = "";
					// //////////////////////////////////////////////////////////////////////////////////////////////////// //
					

						
						header("Location: ../gallery.php?action=edit&id=$id");
						set_time_limit(300);//for uploading big files
						
						$paths="asanti/img/gallery/" . $id;
					
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
							
							array_push($filesList, $name);
							// check the upload status
							// if (!$upload) {
								// echo "FTP upload has encountered an error!";
							   // } else {
							   		// array_push($filesList, $name);
							       	// echo "Uploaded file with name $name to $ftp_server </br>";
							   // }
						
							chdir("../../img/gallery/" . $id . "/");
							foreach(glob($name) as $filename) {
							    echo $filename . "</br>";
							    
								list($width, $height) = getimagesize($filename);
								echo("Current width: " . $width . "  Current height: " . $height . "</br>");
								
								$maxWidth = 900;
								$maxHeight = 900;
								
								if($width > $maxWidth){
									if($width > $height){
										$currentAspect = $height/$width;
										$newwidth = $maxWidth;
										$newheight = $newwidth * $currentAspect;
									}
									if($width < $height){
										$currentAspect = $width/$height;
										$newheight = $maxHeight;
										$newwidth = $newheight * $currentAspect;
									}
									if($width == $height){
										$newwidth = $maxWidth;
										$newheight = $maxHeight;
									}
								}else{
									
								if($height > $maxHeight){
									if($width > $height){
										$currentAspect = $height/$width;
										$newwidth = $maxWidth;
										$newheight = $newwidth * $currentAspect;
									}
									if($width < $height){
										$currentAspect = $width/$height;
										$newheight = $maxHeight;
										$newwidth = $newheight * $currentAspect;
									}
									if($width == $height){
										$newwidth = $maxWidth;
										$newheight = $maxHeight;
									}
								}	
								else{
									
								if($width <= $maxWidth && $height <= $maxHeight){
									$newwidth = $width;
									$newheight = $height;
								}
								}
								}
								
								echo "Current aspect ratio: " . $currentAspect . "</br>";
								echo "New width: " . $newwidth . "   New height: " . $newheight . "</br></br>";
								
								// $percent = 0.5;
								// $newwidth = $width * $percent;
								// $newheight = $height * $percent;
								
								// Load
								$thumb = imagecreatetruecolor($newwidth, $newheight);
								$source = imagecreatefromjpeg($filename);
								echo($filename);
								
								// Resize
								imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
								
								// Output
								ob_start();
				   			 	imagejpeg($thumb);
							 	$img = ob_get_clean(); 
								
								// Allows overwriting of existing files on the remote FTP server
								$stream_options = array('ftp' => array('overwrite' => true));
								
								// Creates a stream context resource with the defined options
								$stream_context = stream_context_create($stream_options);
							
							
								$fp = fopen("ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . "/" . $paths . "/" . $filename, "w", 0, $stream_context);
								fwrite($fp, $img);						
							}
						}
						
						
						
						// Add files to SQL ///////////////////////////////////////////////////////////////////
						
						
						if (mysqli_connect_errno())
						{
					 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
						
						
						$result = mysqli_query($conn,"SELECT MAX(orderN) AS orderN FROM gallery_photos WHERE gallery_id = $id");
															
						while($row = mysqli_fetch_array($result))
							{
								$lastOrderN = $row["orderN"];
							}
						
						// TO FIX!!!!!!!!!!!!!!!! ////////////////////////////////////////
						/////////////////////////////////////////////////////////////////
						///////////////////////////////////////////////////////////////
						
						$i=$lastOrderN + 1;
						
						foreach($filesList as $file){
							$sql = ("INSERT INTO gallery_photos (name, gallery_id, url, orderN) 
								VALUES ('" .  $file . "', '" . $id . "', 'http://serwer1309748.home.pl/asanti/img/gallery/" . $id	 . "/" . $file . "', '$i')");
								$i++;
						if (!mysqli_query($conn,$sql))
					  	{
					  		die('Error: ' . mysqli_error($conn));
					  	} else {
					  		echo $i . " record added </br>";
							$i++;
					  	}
						
						}
						
						// close the FTP connection
						ftp_close($conn_id);	
						
				        break;
}
?>