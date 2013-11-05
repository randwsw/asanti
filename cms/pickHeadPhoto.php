<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

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

$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);

// CREATE ITEM
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }




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
		
	
	set_time_limit(300);//for uploading big files
	
	$paths="asanti/img/items/" . $lastId;

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
		
		$upload = ftp_put($conn_id, $paths.'/'.$name, $filep, FTP_BINARY);
	
		// check the upload status
		if (!$upload) {
			// echo "FTP upload has encountered an error!";
		   } else {
		   		array_push($filesList, $name);
		       	// echo "Uploaded file with name $name to $ftp_server </br>";
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
}
?>



<?php

if(isset($_GET['itemId'])){
	$itemId = $_GET['itemId'];
}else{
	$itemId = $lastId;
}

class photo {
	public $id;
	public $url;
}
										
$photoList = array();
$headPhotoUrl = "";									
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
										
										
if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
										
										
										
$result = mysqli_query($conn,"SELECT * FROM photo WHERE item_id = $itemId AND isHEadPhoto = '0' ORDER BY orderN ASC");
										
while($row1 = mysqli_fetch_array($result))
	{
		$photo = new photo;
		$photo->id = $row1['id'];
		$photo->url = $row1['url'];
		array_push($photoList, $photo);
	}

$result2 = mysqli_query($conn,"SELECT * FROM photo WHERE id = (SELECT headPhotoId FROM item WHERE id = $itemId)");

while($row2 = mysqli_fetch_array($result2))
	{
		$headPhotoUrl = $row2['url'];
	}	
	
	
mysqli_close($conn);
?>


<html>
	<head>
		<?php include "../include/links.php"; ?>
		
		<script src="<http://deepliquid.com/Jcrop/js/jquery.Jcrop.min.js>"></script>
		<script src="../js/jcrop/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="../js/jcrop/jquery.Jcrop.css" type="text/css" />
		<link rel="stylesheet" href="../css/demos.css" type="text/css" />
		<link rel="stylesheet" href="../css/jquery.Jcrop.css" type="text/css" />
		<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
		
		<script type="text/javascript">
		
		function thumbnailsClick(){
				$(".photoThumb").click(function(){
					
					$(".photoThumb").css("opacity", "1.0");
					$(this).css("opacity", "0.4");
					$("#cropbox").css("margin-left", "0px");
					$("#cropTip").css("display", "none");
					$("#proceedCrop").css("display", "none");
					$("#cropThis").css("display", "inline");
					
					JcropAPI = $('#cropbox').data('Jcrop');
					$("#cropbox").css("width", "auto");
					$("#cropbox").css("height", "auto");
					var url = $(this).attr('src');
					$('#cropbox').attr('src', url);
					$("#imgUrl").val(url);
					JcropAPI.destroy();	
				})
			}
		
		
		$( document ).ready(function() {
			
			thumbnailsClick();
			
			
			
			
			// function readURL(input) {
        		// if (input.files && input.files[0]) {
            		// var reader = new FileReader();
//             
            		// reader.onload = function (e) {
    					// JcropAPI = $('#cropbox').data('Jcrop');
						// $("#cropbox").css("width", "auto");
						// $("#cropbox").css("height", "auto");
                		// $('#cropbox').attr('src', e.target.result);
                			// JcropAPI.destroy();			
           			// }
//             
            		// reader.readAsDataURL(input.files[0]);
        		// }
    		// }
//     
		    // $("#imgInp").change(function(){
		        // readURL(this);
		    // });
	
	
			$("#cropThis").click(function(){
				cropThis();
				$(this).css("display", "none");
				$("#proceedCrop").css("display", "inline");
			})		
			
			function cropThis(){
				$(function(){
  
                	$('#cropbox').Jcrop({
                   		aspectRatio: 22 / 30,
                   		minSize: [300, 409],
                    	onSelect: updateCoords
                	});
  
            	});
  
            	function updateCoords(c){
	                $('#x').val(c.x);
	                $('#y').val(c.y);
	                $('#w').val(c.w);
	                $('#h').val(c.h);
            	};
  
	            function checkCoords()
	            {
	                if (parseInt($('#w').val())) return true;
	                // alert('Select to crop.');
	                return false;
	            };
   			}

		});
	</script>
		
		
	</head>
	
	<body>
		
		
		
		
		<div id="siteContainer">
			
			<a href="../index.php"><div>GO TO SHOP</div></a>
			
			<div id="header"><div id="title">ASANTI CMS</div><div id="subtitle">Wybierz zdjęcie główne</div></div>
			
			<div id="container">
				
				<div id="leftMenu">
					
					<!-- Include links ---------------------------------------------------------- -->
					<?php include 'include/leftMenu.php'; ?>
					<!-- ------------------------------------------------------------------------ -->
					
				</div>
				
				<div id="rightContent">
					
					<div id="container">
						
						
						
						<div class="label">Ustaw zdjęcie główne:</div>
						
						<div id="pickPhoto">
							
							<div id="headPhoto">
								
								<div id="container">
									
									<img src="<?php echo($headPhotoUrl); ?>" />
									
								</div>
								
							</div>
							
							<div id="thumbnails">
								
								<div class="container">
									
									<ul class="thumbs noscript">
										<?php 
											
											foreach($photoList as $photo){
												echo("<li>
															<div class='frame'><span class='helper'></span>
																<img src='$photo->url' class='photoThumb' id='itemPhoto$photo->id'/>
															</div>	
													</li>")	;
												
												// echo('<img class="itemPhotoThumbnails" id="itemPhoto' . $photo->id . '" src="' . $photo->url . '" style="max-width: 200px; max-height: 200px;"/>');
											}
											
											
										?>
									</ul>
								</div>
							</div>
							
						</div>
						
						
						
						
						
						<div id="asd" style="width: 400px; height: 150px; background-color: none;"></div>
						<form action="controllers/photosController.php" method="post" onsubmit="return checkCoords();">
							<input type="hidden" name="action" id="action" value="pickHead" />
							<div id="cropButtons">
								<div id="cropTip">Wybierz zdjęcie</div>
								<input type="button" id="cropThis" value="Dopasuj zdjęcie"/>
								<input type="submit" id="proceedCrop" value="Ustaw zdjęcie główne"/>
							</div>
							
					        <img id="cropbox" src="#" width="" height="" alt="your image" />
					        
					        <input type="hidden" id="imgUrl" name="imgUrl" value="" />
					        
					        <input type="hidden" id="passItemId" name="passItemId" value="<?php echo($itemId); ?>" />
					        <input type="hidden" id="x" name="x" />
				            <input type="hidden" id="y" name="y" />
				            <input type="hidden" id="w" name="w" />
				            <input type="hidden" id="h" name="h" />
				            
					        <input type="hidden" name="action" value="pickHead" />
						</form>
						
						
					</div>				
				</div>
			</div>	
			<!-- Include footer --------------------------------------------------------- -->
			<?php include 'include/footer.php'; ?>
			<!-- ------------------------------------------------------------------------ -->
		</div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	</body>
</html>