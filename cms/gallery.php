<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}
?>




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
	
	
	
	function getGalleries(){
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/galleryController.php', 
		    data: {action: "getGalleries"},
		    dataType: 'json',
		    error: function (data) {
		    	// alert("error");
		    },
		    success: function (data) { 
		    	$("#galleriesTable").html("<tr class='header'><td class='img'></td>"
		    	+ "<td class='title'>Nazwa</td>"
		    	+ "<td class='options'>Opcje</td>");

		    	var j = 0;
		    	$.each(data,function(i,row){
		    		
		    		if(row.itemActive == 1){
		    			var checked = "checked";
		    		}else{
		    			var checked = "";
		    		}
		    		
		    		if(j%2 == 0){
		    			var trClass = "odd";
		    		}else{
		    			var trClass = "even";
		    		}
		    		
		    		$("#galleriesTable").append(
		    			"<tr class='" + trClass + "'><td class='img' id='imgTd" + row.id + "'><img src='" + row.headPhoto 
		    			+ "'class='img' /></td><td class='title' id='titleTd" + row.id + "'>" + row.title
		    			+ "</td><td class='options'>"
		    			+ "<a href='gallery.php?action=edit&id=" + row.id + "' ><input type='button' class='edit' value='Edytuj' id='editButton" + row.id + "'/></a>"
		    			+ "<input type='button' class='delete' value='Usuń' id='deleteButton" + row.id + "'/>"
		    			// + "<input type='checkbox' class='itemActiveCheckbox' id='active" + row.ItemId + "'checked='" + checked + "'/></td></tr>"
		    			// + "<div class='squaredOne'><input type='checkbox' value='None' " + checked + " class='squaredOneCheckbox' id='squaredOne" + row.id + "' name='check' />"
						// + "<label for='squaredOne" + row.id + "'></label></div>"
						+ "</td></tr>"
		    		);
		    		j++;

		    	})
		    	deleteGallery();
		    	editButton();
		    	
		    	changeActiveItem();
		    	filter();
		    	sort();
			},
		})
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
		
		function deleteGallery(){
			$("input.delete").click(function(){
			if(!window.confirm("Na pewno chcesz usunąć tę galerię?")){
	            return false;
	        }else{
			var galleryId = $(this).attr("id");
			galleryId = galleryId.substr(12,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/galleryController.php', 
			    data: {action: "delete", galleryToDelete: galleryId},
			    timeout: 50000,
			    beforeSend: function(){
			    	
			    },
			    complete: function(){
			    	
			    },
			    error: function (data) {
			    	// alert("ajaxError");
			    },
			    success: function (data) {
			    	$("#galleriesTable").html("");
			    	getGalleries();
			    	$("#confirmAlert").fadeIn("fast");
					$("#confirmAlert").delay(800).fadeOut(800);
				},
			})
		}
		});
		}
		
		
		function deletePhoto(){
			$(".deletePhoto").click(function(){
				var photoId = $(this).attr("id").substr(11,10);
				var id = getURLParameter("id");
				if(!window.confirm("Na pewno chcesz usunąć to zdjęcie?")){
		            return false;
		        }else{
					$.ajax({ 
				    type: 'POST', 
				    url: 'controllers/galleryController.php', 
				    data: {action: "deletePhoto", id: id, photoId: photoId},
				  
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
		
		
		
		function moveBefore(){
			$(".moveBefore").click(function(){
				var photoId = $(this).attr("id").substr(10,10);
				var id = getURLParameter("id");
				var direction = "left";
				
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/galleryController.php', 
			    data: {action : "move", photoId: photoId, id: id, direction: direction},
			  
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
				var id = getURLParameter("id");
				var direction = "right";
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/galleryController.php', 
			    data: {action : "move", photoId: photoId, id: id, direction: direction},
			  
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
		
		
		function getPhotos(){
				var id = getURLParameter("id");

				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/galleryController.php', 
			    data: {action : "getPhotos", id : id},
			  
			    error: function (data) {
			    	// alert("porażka!");
			    },
			    success: function (data) {
			    	$(".thumbs").html(data);
			    	moveBefore();
					moveAfter();
					deletePhoto();
				},
			})
		}
		
		
		
		$(document).ready(function(){
			$("#confirmAlert").hide();
			var action = getURLParameter("action")
			
			if(action == "edit"){
				getPhotos();
				showPreview();
			}
			if(action == null){
				getGalleries();
				showPreview();
			}
			if(action == "add"){
				showPreview();
			}
		});
		
		</script>
  
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header">
			<div id="title">ASANTI CMS</div>
			<div id="subTitle">
				<?php if(isset($_GET['action']) && $_GET['action'] == "add"){ ?>
					Utwórz nową galerię 
				<?php } ?>
				<?php if(!isset($_GET['action'])){ ?>
					Galerie
				<?php } ?>
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
					
					<?php if(!isset($_GET['action'])){
						?>			
								
					<div id="galleries">
						<div id="confirmAlert">Usunięto galerię</div>
						<a href="gallery.php?action=add"><input type="button" id="addNew" value="Stwórz nową galerię"/></a>
						<table id="galleriesTable">
							
						</table>
					</div>		
						
					<?php }?>
					
					
					<?php if(isset($_GET['action']) && $_GET['action'] == "add"){
						?>
					<input type="button" onclick="history.go(-1)" value="Powrót" />
					<div id="newGallery">
						<form action="controllers/addGPhotos.php"; method="POST" enctype="multipart/form-data">
							
							<div id="name">
								
								<div class="label">Tytuł galerii:</div>
								
								<input id="name" name="name" type="text" value="" />
								
							</div>
							
		        			
		        			
							<div id="photos">
								
								<div class="label">Wybierz zdjęcia:</div>
								
								<input name="userfile[]" id="image-input" type="file" multiple="multiple" accept="image/*">
								
								<div class="preview-area"></div>
								
							</div>
							
							<input type="hidden" name="action" value="add" />
							<input type="submit" name="submit" value="Dalej" />
							
						</form>
					
					</div>
					<?php } ?>
					
					<?php if(isset($_GET['action']) && $_GET['action'] == "edit"){
						
					$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
					$id = $_GET['id'];
					
					$sql = "SET NAMES 'utf8'";
					!mysqli_query($conn,$sql);
					
					
					if (mysqli_connect_errno())
							  {
							  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
							  }
							  
					
					$result = mysqli_query($conn,"SELECT g.id AS id, g.title AS title FROM gallery g WHERE id = $id GROUP BY g.id");
					
					while($row1 = mysqli_fetch_array($result))
					{
						$title=$row1['title'];
					}

					
						  
						?>
					<div id="gallery">
					<div id="confirmAlert">Usunięto zdjęcie</div>			
	
						<form action="controllers/galleryController.php"; method="POST" enctype="multipart/form-data">
								
								<div id="title">
									<div class="label">Tytuł galerii:</div>
									<input class="nameInput" name="name" type="text" value="<?php echo($title); ?>"/>
								</div>
								<input type="submit" name="submit" value="Zatwierdź" />
								<div id="photos">
									<div id="container">
										<div class="label">Zdjęcia:</div>
										
										<div id="thumbnails">
											
											<div class="container">
												
												<ul class="thumbs noscript">
												
												</ul>
												
											</div>
											
										</div>
									</div>
									
									
								</div>
								<input type="hidden" name="action" value="changeTitle" />
								<input type="hidden" name="galleryId" value="<?php echo($_GET['id']); ?>" />		
								<input type="submit" name="submit" value="" style="visibility: hidden;" />
							</form> 
							        
							<form action="controllers/galleryController.php"; method="POST" enctype="multipart/form-data">
				        			
								<div id="addPhotos">
										
									<div class="label">Dodaj więcej zdjęć:</div>
										
									<input name="userfile[]" id="image-input" type="file" multiple="multiple" accept="image/*">
										
									<div class="preview-area"></div>
										
								</div>
									
								<input type="hidden" name="action" value="addPhotos" />
								<input type="hidden" name="galleryId" value="<?php echo($_GET['id']); ?>" />
								<input type="submit" name="submit" value="Dodaj zdjęcia" />
								<a href="gallery.php"><input type="button" name="goBack" value="Wróć" /></a>
									
							</form> 
								<!-- <input type="hidden" id="passGalleryId" name="passGalleryId" value="" /> -->
							 
					</div>
					<?php } ?>
				</div>	
							
			</div>
			
		</div>	
		
		
		<div id="footer">ASANTI CMS FOOTER</div>
		
	</div>
	
</body>
	
	
	
	
	
	
	
	
	
		
</html>
