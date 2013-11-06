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