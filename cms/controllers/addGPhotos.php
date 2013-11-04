<?php
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

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
			
			$upload = ftp_put($conn_id, $paths.'/'.$name, $filep, FTP_BINARY);
		
			// check the upload status
			if (!$upload) {
				echo "FTP upload has encountered an error!";
			   } else {
			   	$name = $purifier->purify($name);
			   		array_push($filesList, $name);
			       	echo "Uploaded file with name $name to $ftp_server </br>";
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