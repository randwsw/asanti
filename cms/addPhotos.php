<?php 
	if(!session_id()){
		session_start();
	} 
?>
<!-- Check login ------------------------------------------------------------ -->
<?php include 'include/checkLog.php'; ?>	
<!-- ------------------------------------------------------------------------ -->

<?php

	if(!isset($_POST["submit"])){
	
	$itemId = $_GET['itemId'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
	<?php include "../include/links.php"; ?>


  
<script type="text/javascript">

		function showPreview(){
			var inputLocalFont = document.getElementById("image-input");
			inputLocalFont.addEventListener("change",previewImages,false);
			
			function previewImages(){
			    var fileList = this.files;
			    
			    var anyWindow = window.URL || window.webkitURL;
	
	        	for(var i = 0; i < fileList.length; i++){
	          		var objectUrl = anyWindow.createObjectURL(fileList[i]);
	          		$('.preview-area').append('<img src="' + objectUrl + '" style="max-width: 150px; max-height: 150px;"/>');
	          		window.URL.revokeObjectURL(fileList[i]);
	        	}
			}
		}
		
		
		// function selectSizeStartup(){
			// var sizeOf="height";
			// $.ajax({ 
								// url: "controllers/getAllSize.php",
								// type: "POST",
								// data:  {sizeOf: sizeOf},
								// cache: false
								// }).done(function(data) {
						  		// $("#sizePickBox").html(data);
							// });
		// }
		
		
		function getPhotos(){
				var itemId = $("#passItemId").val();
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/photosController.php', 
			    data: {action : "getAll", itemId: itemId},
			  
			    error: function (data) {
			    	// alert("porażka!");
			    },
			    success: function (data) {
			    	$(".thumbs").html(data);
					$(".moveBefore").css("display", "none");
					$(".moveAfter").css("display", "none");
					$(".deletePhoto").css("display", "none");
				},
			})
		}
		
	
		
		$(document).ready(function(){
			getPhotos();
			showPreview();
		});
		
		</script>
  
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		<div id="header"><div id="title">ASANTI CMS</div><div id="subTitle">Dodaj zdjęcia</div></div>
		
		<div id="container">
			
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			
			
			<div id="rightContent">
				
				
				<div id="container">
					
					
					<a href="editItem.php?itemId=<?php echo($itemId); ?>"><input type="button" name="goBack" value="Powrót" /></a>
					
					
					
					<div id="thumbnails">
						
						<div class="label">Zdjęcia przedmiotu:</div>
						
						<div id="container">
							
							<ul class="thumbs noscript">
									
							</ul>
							
						</div>
						
					</div>
					
					
					<form action="addPhotos.php"; method="POST" enctype="multipart/form-data">
						
						
						<div id="addPhotos">
							
							<div id="container">
								<input type="hidden" id="passItemId" name="passItemId" value="<?php echo($itemId); ?>" />
								<div class="label">Dodaj nowe zdjęcia:</div>
								<input name="userfile[]" id="image-input" type="file" multiple="multiple" accept="image/*">
								<div class="preview-area"></div>
								<input type="submit" name="submit" value="Dodaj zdjęcia" />
								
							</div>
							
						</div>
							
							
						
						
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

<?php }
else 
{
			
			
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$itemId = $_POST["passItemId"];
$lastOrderN = "";
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

	
	
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
	
	header("Location: editItem.php?itemId=" . $itemId);
}
?>