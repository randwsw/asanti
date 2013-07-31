
<?php
if(!isset($_POST["submit"])){?>
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
<form action="addHeadPhoto.php" method="post" enctype="multipart/form-data">

	<input type='file' name="imgInP" id="imgInp" />
	<img id="cropbox" src="#" alt="your image" />
	<!-- <button id="cropThis" onclick="return false;">Crop</button> -->
	
	<input type="hidden" name="passItemId" id="passItemId"></input>
	
	
	<!-- <input type="hidden" id="imgUrl" name="imgUrl" />
	<input type="hidden" id="x" name="x" />
	<input type="hidden" id="y" name="y" />
	<input type="hidden" id="w" name="w" />
	<input type="hidden" id="h" name="h" /> -->
	
	<input type="submit" value="submit" name="submit">
</form>

</body>
</html>
<?php }else
	{
		

$host = "serwer1309748.home.pl";
$username = "serwer1309748";
$password = "9!c3Q9";
$local_file = $_FILES['imgInP']['tmp_name'];
$dir = "asanti/img/items/" . "13";
$remote_file = $dir . '/' . $_FILES['imgInP']['name'];

$con = ftp_connect($host, 21) or die("Could not connect to FTP server");
$log = ftp_login($con, $username, $password) or die("Fail to longin");
// ftp_mkdir($con, $dir);
$upload = ftp_put($con, $remote_file, $local_file, FTP_ASCII);
if($upload) echo 'Success!';
ftp_close($con);


	}
?>