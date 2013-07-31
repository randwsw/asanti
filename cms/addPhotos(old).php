<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $targ_w = $targ_h = 150;
    $jpeg_quality = 90;
  
    $src = $_POST['imageUrl'];
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
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	
	<!-- Include links ---------------------------------------------------------- -->
	<?php include '../include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	
	<script src="<http://deepliquid.com/Jcrop/js/jquery.Jcrop.min.js>"></script>
	<script src="../js/jcrop/jquery.Jcrop.js"></script>
	<link rel="stylesheet" href="../js/jcrop/jquery.Jcrop.css" type="text/css" />
	<link rel="stylesheet" href="demo_files/demos.css" type="text/css" />
	
	
	
	<script type="text/javascript">
		$( document ).ready(function() {
			
			
			function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
    			JcropAPI = $('#cropbox').data('Jcrop');
				$("#cropbox").css("width", "auto");
				$("#cropbox").css("height", "auto");
                $('#cropbox').attr('src', e.target.result);
                	JcropAPI.destroy();			
             	
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#imgInp").change(function(){
        readURL(this);
        
    });
	
	
	$("#cropThis").click(function(){
		cropThis();
	})		
		function cropThis(){
			$(function(){
  
                $('#cropbox').Jcrop({
                    aspectRatio: 1,
                    onSelect: updateCoords
                });
  
            });
  
            function updateCoords(c)
            {
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
	
	<script language="Javascript">
  
            
  
        </script>
	
	
	</head>
<body>
Ustaw zdjęcie główne:
<form action="controllers/addPhotoController.php" method="post"
enctype="multipart/form-data">

<div id="cropThis" value="Crop" name="Crop" style="border: 2px solid Black;">Crop</div>
<input type='file' id="imgInp" />
        <img id="cropbox" src="#" width="" height="" alt="your image" />

<!-- <p style=" float: left; margin-top: 2px">Dodaj zdjęcie:   </p>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit"> -->
</form>

</body>
</html>