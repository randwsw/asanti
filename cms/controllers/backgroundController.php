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
// //////////////////////////////////////////////////////////////////////////////////////////////////// //



$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);


if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }

require_once '../../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);


$action = $_POST['action'];






switch ($action) {
	
	case "getAll":
	// ---------------------------------------------------------------------------------------------------------- //
	// GET ALL PHOTOS ------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		class photo {
			public $id;
			public $url;
		}
												
		$photoList = array();		
						
		$result3 = mysqli_query($conn,"SELECT * FROM background ORDER BY orderN ASC");
												
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
		
		
		
		
		
		case "move":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE PHOTOS ORDER -------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$photoId = $_POST['photoId'];
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
			
			
			
			$result = mysqli_query($conn, "SELECT orderN FROM background WHERE id = $photoId");
		
			while($row = mysqli_fetch_array($result))
				{
					$orderN = $row['orderN'];
				}	
				
				
				
			$result2 = mysqli_query($conn, "SELECT MAX(orderN) AS maxOrderN FROM background");
			
			while($row2 = mysqli_fetch_array($result2))
				{
					$maxOrderN = $row2['maxOrderN'];
				}	
			
			
			
			if($orderN != "1" && $direction == "left"){
				
				$before = $orderN - 1;
				
				$result3 = mysqli_query($conn, "SELECT id FROM background WHERE orderN = $before");
			
				while($row3 = mysqli_fetch_array($result3))
					{
						$id2 = $row3['id'];
					}	
					
				mysqli_query($conn,"UPDATE background SET orderN = $orderN WHERE id=$id2");
				mysqli_query($conn,"UPDATE background SET orderN = $before WHERE id='$photoId'");
			}else{
				if($orderN < $maxOrderN && $direction == "right"){
					
					$next = $orderN + 1;
					
					$result4 = mysqli_query($conn, "SELECT id FROM background WHERE orderN = $next");
			
					while($row4 = mysqli_fetch_array($result4))
						{
							$id2 = $row4['id'];
						}	
						
					mysqli_query($conn,"UPDATE background SET orderN = $orderN WHERE id=$id2");
					mysqli_query($conn,"UPDATE background SET orderN = $next WHERE id='$photoId'");
				}
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
					
				
					$lastOrderN = "";
					// //////////////////////////////////////////////////////////////////////////////////////////////////// //
					
						
						header("Location: ../background.php");
						set_time_limit(300);//for uploading big files
						
						$paths="asanti/img/background";
					
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
						
						
						$result = mysqli_query($conn,"SELECT MAX(orderN) AS orderN FROM background");
															
						while($row = mysqli_fetch_array($result))
							{
								$lastOrderN = $row["orderN"];
							}
						
						// TO FIX!!!!!!!!!!!!!!!! ////////////////////////////////////////
						/////////////////////////////////////////////////////////////////
						///////////////////////////////////////////////////////////////
						
						$i=$lastOrderN + 1;
					
						foreach($filesList as $file){
							$sql = ("INSERT INTO background (name, url, orderN) 
								VALUES ('" .  $file . "', 'http://serwer1309748.home.pl/asanti/img/background/" . $file . "', '$i')");
								$i++;
						if (!mysqli_query($conn,$sql))
					  	{
					  		die('Error: ' . mysqli_error($conn));
					  	} else {
					  		// echo $i . " record added </br>";
							// $i++;
					  	}
						
						}					
			break;
			
			
			
			
			
			case "deletePhoto":
			// ---------------------------------------------------------------------------------------------------------- //
			// DELETE PHOTO --------------------------------------------------------------------------------------------- //
			// ---------------------------------------------------------------------------------------------------------- //
			// ---------------------------------------------------------------------------------------------------------- //
			// ---------------------------------------------------------------------------------------------------------- //
		
				$photoId = $_POST['photoId'];
				$orderN = "";
				$nextOrderN = "";
				$decreasedOrderN = "";
				$nextItem = "";
				$countItems = "";
				
				
				$result = mysqli_query($conn, "SELECT orderN, name FROM background WHERE id = $photoId");
				
				while($row = mysqli_fetch_array($result))
					{
						$orderN = $row['orderN'];
						$file = $row['name'];
					}	
				
				
				
				
				$result2 = mysqli_query($conn, "SELECT COUNT(*) AS countPhotos FROM background WHERE orderN > $orderN");
				
				while($row2 = mysqli_fetch_array($result2))
					{
						$countPhotos = $row2['countPhotos'];
					}	
				
				//DELETE PHOTO
				$sql="DELETE FROM background
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
						
						$result3 = mysqli_query($conn, "SELECT id FROM background WHERE orderN = $nextOrderN");
					
						while($row3 = mysqli_fetch_array($result3))
							{
								$nextPhoto = $row3['id'];
							}	
							
						$decreasedOrderN = $nextOrderN - 1;	
						$nextOrderN++;
						// echo("photo_" . $nextItem . "    don_" . $decreasedOrderN . "    non_" . $nextOrderN . "</br>");
						
						mysqli_query($conn,"UPDATE background SET orderN = $decreasedOrderN WHERE id=$nextPhoto");
					}
				}
			
				mysqli_close($conn);
				
				
				

				// DELETE FILES AND DIR ON FTP SERVER

				set_time_limit(300);//for uploading big files
				
				$paths="/asanti/img/background";
			
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
}
?>