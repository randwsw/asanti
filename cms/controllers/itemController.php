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
	
	case "getAll":
	// ---------------------------------------------------------------------------------------------------------- //
	// GET ALL ITEMS -------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	$page = 1;
									
	if(isset($_POST['page'])) {	$page =$_POST['page'];	} 
									
	$itemsPerPage = 16;
	$min = $itemsPerPage*($page-1);
	
	
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
											. $sort." LIMIT $min, $itemsPerPage");
											
				
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
		
		
		
		// DELETE COLOR ITEM CONNECTIONS
		
		$sql14="DELETE FROM colors_conn
					WHERE item_id = '$itemToDelete'";
				if (!mysqli_query($conn,$sql14))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  		
			  	}
		
		// DELETE RECOMMENDED PRICE	
			
		$sql15="DELETE FROM rec_price
					WHERE rec_id = (SELECT id FROM recommended WHERE item_id = '$itemToDelete')";
				if (!mysqli_query($conn,$sql15))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  		
			  	}
				
		// DELETE RECOMMENDED CONNECTIONS	
		
		$sql16="DELETE FROM recommended
					WHERE item_id = $itemToDelete";
				if (!mysqli_query($conn,$sql16))
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
		
			$paths2="/asanti/img/items/" . $itemToDelete . "/head";
			
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
		   
		   
			ftp_chdir($conn_id, $paths2);
			$files2 = ftp_nlist($conn_id, ".");
			foreach ($files2 as $file2)
			{
			    ftp_delete($conn_id, $file2);
			}    
				ftp_rmdir($conn_id, $paths2);
			
			
			
		
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
			
			
			
			
		case "addItem":
		// ---------------------------------------------------------------------------------------------------------- //
		// ADD NEW ITEM --------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //

		$name = $conn->real_escape_string($_POST['name']);
		$name = $purifier->purify($name);
		
		$description = $conn->real_escape_string($_POST['description']);
		// $description = $purifier->purify($desciption);
		
		$headPhotoId = 0;
		
		$category = $conn->real_escape_string($_POST['categoryToPost']);
		$category = $purifier->purify($category);
		
		$price = $conn->real_escape_string($_POST['price']);
		$price = $purifier->purify($price);
		
		
		// //////////////////////////////////////////////////////////////////////////////////////////////////// //
		
		
		$sql="INSERT INTO item (name, description, headPhotoId, price)
		VALUES
		('$name','$description','$headPhotoId', '$price')";
		
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
				
				$result = mysqli_query($conn,"SELECT id FROM item ORDER BY id DESC LIMIT 1 ");
				while($row2 = mysqli_fetch_array($result))
				  {
						$lastId = $row2['id'];
				  }
			
		  }
		  
		  
		  
		  
		// ADD SIZE_ITEM
		
		if(!empty($_POST['pickSize'])) {
		    foreach($_POST['pickSize'] as $check) {
		    	
				if (mysqli_connect_errno())
			  	{
			  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  	}
				
				
				$sql2="INSERT INTO size_item (sizeId, itemId)
					VALUES
					('$check','$lastId')";
				if (!mysqli_query($conn,$sql2))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		// mysqli_close($conn3);
			  	}else
			  	{
					// mysqli_close($conn3);
			  	}
			}
		}
		
		  
		  
		    		
		// ADD CATEGORY
		
		if(!empty($category)) {
		    
				if (mysqli_connect_errno())
			  	{
			  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  	}
				
				
				$sql3="INSERT INTO category_con (item_id, cat_id)
					VALUES
					('$lastId','$category')";
				if (!mysqli_query($conn,$sql3))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		// mysqli_close($conn3);
			  	}else
			  	{
					// mysqli_close($conn3);
			  	}
		}			
					
		
		// ADD COLOR
		
		if(!empty($_POST['pickColor'])) {
		    foreach($_POST['pickColor'] as $check2) {
		    	if (mysqli_connect_errno())
			  	{
			  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  	}
				
				
				$sql5="INSERT INTO colors_conn (color_id, item_id)
					VALUES
					('$check2','$lastId')";
				if (!mysqli_query($conn,$sql5))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		// mysqli_close($conn3);
			  	}else
			  	{
					// mysqli_close($conn3);
			  	}
			}
		}				
				
			header("Location: ../pickHeadPhoto.php?itemId=$lastId");
			set_time_limit(300);//for uploading big files
			
			$paths="asanti/img/items/" . $lastId;
		
			$ftp_server="serwer1309748.home.pl";
		
			$ftp_user_name="serwer1309748";
		
			$ftp_user_pass="9!c3Q9";
		
			$filesList = array();
		
		
			// error_reporting(0);
			// set up a connection to ftp server
			$conn_id = ftp_connect($ftp_server);
			
			// login with username and password
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		
			// check connection and login result
			if ((!$conn_id) || (!$login_result)) {
				// echo "FTP connection has encountered an error!";
				// echo "Attempted to connect to $ftp_server for user $ftp_user_name....";
				exit;
			   	} else {
			       	// echo "Connected to $ftp_server, for user $ftp_user_name".".....";
			   	}
		   
		   
			ftp_mkdir($conn_id, $paths);
		
				$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
				$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
						
						
						
				for($i=0; $i<count($_FILES['userfile']['name']); $i++){
							
					$filep2=$_FILES['userfile']['tmp_name'][$i];
					$filep =  str_replace($aWhat, $aOn, $filep2);
							
					$name2=$_FILES['userfile']['name'][$i];	
					$name =  str_replace($aWhat, $aOn, $name2);
					
					array_push($filesList, $name);

					$upload = ftp_put($conn_id, $paths.'/'.$name, $filep, FTP_BINARY);
					
					// chdir("../img/items/" . $lastId . "/");
					chdir("../../img/items/" . $lastId . "/");

					echo(getcwd());
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
			}
			// close the FTP connection
			ftp_close($conn_id);	
			
			
			// Add files to SQL ///////////////////////////////////////////////////////////////////
			
			
			if (mysqli_connect_errno())
			{
		 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			$i="1";
			foreach($filesList as $file){
				$sql = ("INSERT INTO photo (name, item_id, url, orderN) 
					VALUES ('" . $file . "', '" . $lastId . "', 'http://serwer1309748.home.pl/asanti/img/items/" . $lastId . "/" . $file . "', '$i')");
					$i++;
			if (!mysqli_query($conn,$sql))
		  	{
		  		die('Error: ' . mysqli_error($conn));
		  	} else {
		  		// echo $i . " record added </br>";
				// $i++;
		  	}
			
			}
			mysqli_close($conn);


			break;
}
?>