<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}
?>

<!-- 
	automatyczne generowanie optionów w select dla rozmiarów
 -->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
	<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
	<?php include "../include/links.php"; ?>


  
<script type="text/javascript">
		
		function getURLParameter(name) {
		    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
		}
	
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
		
		
		function getPhotos(){
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/backgroundController.php', 
			    data: {action : "getAll"},
			  
			    error: function (data) {
			    	// alert("porażka!");
			    },
			    success: function (data) {
			    	$(".thumbs").html(data);
			    	moveBefore();
					moveAfter();
					deletePhoto();
				},
			});
			
		}
		
		
		function moveBefore(){
			$(".moveBefore").click(function(){
				var photoId = $(this).attr("id").substr(10,10);
				var direction = "left";
				
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/backgroundController.php', 
			    data: {action : "move", photoId: photoId, direction: direction},
			  
			    error: function (data) {
			    	// alert("porażka!");
			    },
			    success: function (data) {
			    	// alert("sukces!");
			    	getPhotos();
				},
			})
			})
		}
		
		
		function moveAfter(){
			$(".moveAfter").click(function(){
				var photoId = $(this).attr("id").substr(9,10);
				var direction = "right";
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/backgroundController.php', 
			    data: {action : "move", photoId: photoId, direction: direction},
			  
			    error: function (data) {
			    	// alert("porażka!");
			    },
			    success: function (data) {
			    	// alert("sukces!");
			    	getPhotos();
				},
			})
			})
		}
		
		function deletePhoto(){
			$(".deletePhoto").click(function(){
				var photoId = $(this).attr("id").substr(11,10);
				if(!window.confirm("Na pewno chcesz usunąć to zdjęcie?")){
		            return false;
		        }else{
					$.ajax({ 
				    type: 'POST', 
				    url: 'controllers/backgroundController.php', 
				    data: {action: "deletePhoto", photoId: photoId},
				  
				    error: function (data) {
				    	// alert("porażka!");
				    },
				    success: function (data) {
				    	getPhotos();
				    	$("#confirmAlert").fadeIn("fast");
						$("#confirmAlert").delay(800).fadeOut(800);
					},
				})
			}
			})
		}
		
		$(document).ready(function(){
			$("#confirmAlert").hide();
			getPhotos();
			showPreview();
		});
		
		</script>
  
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header">
			<div id="title">ASANTI CMS</div>
			<div id="subTitle">
				Tło
			</div>
		</div>
		
		<div id="container">
			
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			
			<div id="rightContent">
				
				<div id="container">
					<div id="bgrnd">
					<div id="confirmAlert">Usunięto zdjęcie</div>	
					<form action="controllers/backgroundController.php"; method="POST" enctype="multipart/form-data">
							
							<div id="photos">
								
								<div class="label">Dodaj zdjęcia:</div>
								
								<input name="userfile[]" id="image-input" type="file" multiple="multiple" accept="image/*">
								
								<div class="preview-area"></div>
								
							</div>
							
							<input type="hidden" name="action" value="addPhotos" />
							<input type="submit" name="submit" value="Dalej" />
							
						</form>
					<div id="thumbnails">
						
						<div class="label">Zdjęcia w tle:</div>
						
						<div id="container">
							
							<ul class="thumbs noscript">
									
							</ul>
							
						</div>
						
					</div>
					</div>
				</div>	
							
			</div>
			
		</div>	
		
		
		<div id="footer">ASANTI CMS FOOTER</div>
		
	</div>
	
</body>
	
	
	
	
	
	
	
	
	
		
</html>
