<?php 
	if(!session_id()){
		session_start();
	} 
?>
<!-- Check login ------------------------------------------------------------ -->
<?php include 'include/checkLog.php'; ?>	
<!-- ------------------------------------------------------------------------ -->

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $targ_w = 220;
    $targ_h = 300;
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
   	header("Location: editItem.php?itemId=" . $id);
	
	 $i = ob_get_clean(); 
	
	

	
	
			
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
// //////////////////////////////////////////////////////////////////////////////////////////////////// //


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
}
  
// If isn’t POST, show the page below:
  
?>



<?php


$itemId = $_GET['itemId'];
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
                   		minSize: [220, 300],
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
	                alert('Select to crop.');
	                return false;
	            };
   			}

		});
	</script>
		
		
	</head>
	
	<body>
		
		
		
		
		<div id="siteContainer">
			
			<a href="../shop.php"><div>GO TO SHOP</div></a>
			
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
						<form action="pickHeadPhoto.php" method="post" onsubmit="return checkCoords();">
				
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