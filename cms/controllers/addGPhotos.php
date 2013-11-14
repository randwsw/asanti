<?php
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
	  
	  
	
		header("Location: ../gallery.php");
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
		
		

?>