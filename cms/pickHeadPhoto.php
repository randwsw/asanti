<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $targ_w = 250;
    $targ_h = 320;
    $jpeg_quality = 90;
  
    $src = $_POST['imgUrl'];
    $img_r = imagecreatefromjpeg($src);
    $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
  
    imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
    $targ_w,$targ_h,$_POST['w'],$_POST['h']);
  
    header('Content-type: image/jpeg');
    imagejpeg($dst_r,null,$jpeg_quality);
  
    exit;
}
  
// If isn’t POST, show the page below:
  
?>



<html>
	<head>
		<?php include "../include/links.php"; ?>
		
		<script src="<http://deepliquid.com/Jcrop/js/jquery.Jcrop.min.js>"></script>
		<script src="../js/jcrop/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="../js/jcrop/jquery.Jcrop.css" type="text/css" />
		<link rel="stylesheet" href="../css/demos.css" type="text/css" />
		<link rel="stylesheet" href="../css/jquery.Jcrop.css" type="text/css" />
		
		<script type="text/javascript">
		
		function thumbnailsClick(){
				$(".itemPhotoThumbnails").click(function(){
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
			})		
			
			function cropThis(){
				$(function(){
  
                	$('#cropbox').Jcrop({
                   		aspectRatio: 25 / 32,
                   		minSize: [250, 320],
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
		<div id="itemPhotos">
			<?php 
				
				$itemId = $_GET['itemId'];
				class photo {
					public $id;
					public $url;
				}
				
				$photoList = array();
				
				$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
				
				
				if (mysqli_connect_errno())
					{
				 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
				
				
				
				$result = mysqli_query($conn,"SELECT * FROM photo WHERE item_id = $itemId");
				
				while($row1 = mysqli_fetch_array($result))
					{
						$photo = new photo;
				  	 	$photo->id = $row1['id'];
						$photo->url = $row1['url'];
						array_push($photoList, $photo);
					}
				
				foreach($photoList as $photo){
					echo('<img class="itemPhotoThumbnails" id="itemPhoto' . $photo->id . '" src="' . $photo->url . '" style="max-width: 200px; max-height: 200px;"/>');
				}
				
				
			?>
		</div>
		
		Ustaw zdjęcie główne:
		<form action="pickHeadPhoto.php" method="post" onsubmit="return checkCoords();">

			<div id="cropThis" style="border: 2px solid Black;">Crop</div>

	        <img id="cropbox" src="#" width="" height="" alt="your image" />
	        
	        <input type="hidden" id="imgUrl" name="imgUrl" value="" />
	        
	        
	        <input type="hidden" id="x" name="x" />
            <input type="hidden" id="y" name="y" />
            <input type="hidden" id="w" name="w" />
            <input type="hidden" id="h" name="h" />
            
	        <input type="submit" value="Crop Image" />
		</form>
		
		
	</body>
</html>