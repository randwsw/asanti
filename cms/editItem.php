<?php


$itemId = $_GET['itemId'];
// class photo {
	// public $id;
	// public $url;
// }
										
// $photoList = array();
$headPhotoUrl = "";			
$itemTitle = "";	
$itemPrice = "";
$itemDescription = "";					
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
										
										
if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
										
										
										
$result = mysqli_query($conn,"SELECT * FROM item WHERE id = $itemId");
										
while($row1 = mysqli_fetch_array($result))
	{
		$itemTitle = $row1['name'];
		$itemDescription = $row1['description'];
		$itemPrice = $row1['price'];
	}

$result2 = mysqli_query($conn,"SELECT * FROM photo WHERE id = (SELECT headPhotoId FROM item WHERE id = $itemId)");

while($row2 = mysqli_fetch_array($result2))
	{
		$headPhotoUrl = $row2['url'];
	}	
	
	
// $result3 = mysqli_query($conn,"SELECT * FROM photo WHERE item_id = $itemId AND isHEadPhoto = '0' ORDER BY orderN ASC");
// 										
// while($row3 = mysqli_fetch_array($result3))
	// {
		// $photo = new photo;
		// $photo->id = $row3['id'];
		// $photo->url = $row3['url'];
		// array_push($photoList, $photo);
	// }
	
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
		<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
		
		
		
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
		
		
		
		
		
		
		<script type="text/javascript">
		
		function deletePhoto(){
			$(".deletePhoto").click(function(){
				var photoId = $(this).attr("id").substr(11,10);
				var itemId = $("#passItemId").val();
				if(!window.confirm("Na pewno chcesz usunąć to zdjęcie?")){
		            return false;
		        }else{
					$.ajax({ 
				    type: 'POST', 
				    url: 'controllers/deletePhotoController.php', 
				    data: {photoId: photoId, itemId : itemId},
				  
				    error: function (data) {
				    	alert("porażka!");
				    },
				    success: function (data) {
				    	getPhotos();
					},
				})
			}
			})
		}
		
		
		
		function moveBefore(){
			$(".moveBefore").click(function(){
				var photoId = $(this).attr("id").substr(10,10);
				var itemId = $("#passItemId").val();
				var direction = "left";
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/changeOrderController.php', 
			    data: {photoId: photoId, itemId: itemId, direction: direction},
			  
			    error: function (data) {
			    	alert("porażka!");
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
				var itemId = $("#passItemId").val();
				var direction = "right";
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/changeOrderController.php', 
			    data: {photoId: photoId, itemId: itemId, direction: direction},
			  
			    error: function (data) {
			    	alert("porażka!");
			    },
			    success: function (data) {
			    	// alert("sukces!");
			    	getPhotos();
				},
			})
			})
		}
		
		
		function getPhotos(){
				var itemId = $("#passItemId").val();
				$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/getPhotosController.php', 
			    data: {itemId: itemId},
			  
			    error: function (data) {
			    	alert("porażka!");
			    },
			    success: function (data) {
			    	$(".thumbs").html(data);
			    	moveBefore();
					moveAfter();
					deletePhoto();
				},
			})
		}
		
		function selectSizeStartup(){
			var sizeOf="height";
			$.ajax({ 
								url: "controllers/getAllSize.php",
								type: "POST",
								data:  {sizeOf: sizeOf},
								cache: false
								}).done(function(data) {
						  		$("div#editItem #size #sizes").html(data);
						  		getItemSizes();
						  		changeSize();
							});
		}
		
		
		function selectSize(){
			$("select#options").on("change", function(){
				$("select#options option:selected").each(function () {
					var sizeOf = $(this).attr("value");
					$.ajax({ // loading classes list
								url: "controllers/getAllSize.php",
								type: "POST",
								data:  {sizeOf: sizeOf},
								cache: false
								}).done(function(data) {
						  		$("div#editItem #size #sizes").html(data);
						  		getItemSizes();
						  		changeSize();
							});
				});
			});
		}
		
		
		function getItemSizes(){
			var itemId = $("#passItemId").val();
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/getItemSizes.php', 
			    data: {itemId : itemId},
			    dataType: 'json',
			    error: function (data) {
			    	// alert("error");
			    },
			    success: function (data) { 
			    	$.each(data,function(i,row){
			    		$(":checkbox[value=" + row.sizeId + "]").attr("checked","true");
			    	});
				},
			})
		}
		
		function changeSize(){
			var itemId = $("#passItemId").val();
			var active;
			$(".sizeCheckbox").change(function(){
				if($(this).is(':checked')){
					active = 1;
				}else{
					active = 0;
				}
				var sizeId = $(this).val();			
				$.ajax({ 
				    type: 'POST', 
				    url: 'controllers/changeItemSizeController.php', 
				    data: {itemId: itemId, sizeId : sizeId, active: active},
				    beforeSend: function(){
				    	// $("#progress").show();
				    },
				    complete: function(){
				    	// $("#progress").hide();
				    },
				    error: function (data) {
				    	// alert(active + "error");
				    },
				    success: function (data) {
				    	// alert(active + "success");
					},
				})
			});		
		}
		
		function getItemCategory(){
			var itemId = $("#passItemId").val();
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/getItemCategory.php', 
			    data: {itemId : itemId},
			    // dataType: 'json',
			    error: function (data) {
			    	// alert("error");
			    },
			    success: function (data) { 
			    	$("#category").append(data);
			    	categoriesOnChange();
			    	
			    	// $.each(data,function(i,row){
			    		// getCategories(0);
			    		// getCategories(row['parentId'], row['parentId']);
			    		// $('input[name=categoryRoot][value=5]').prop("checked",true);
			    		// alert(row['categoryId'] + "__" + row['parentId']);
			    		// getCategories(row['parentId']);
			    		// $("#categoryLevel_2 option:eq(8)").prop("selected", true);
			    	// });
				},
			})
		}
		
		function getCategories(parentId){
			
			$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/getCategory.php', 
		    data: {parentId: parentId},
		    error: function (data) {
		    	alert("error");
		    },
		    success: function (data) { 
		    	var selectId;
		    	if(parentId == 0){
		    		selectId = "categoryLevel_0";
		    		$("#" + selectId).html(data);
		    		// $("#categoryLevel_0 option:selected").each(function(){
		    			// getCategories($("#categoryLevel_0 option:selected").val());
		    		// });
		    	}else{
		    		selectId = "categoryLevel_2";
		    		$("#" + selectId).html(data);
		    	}
		    	categoriesOnChange();
			},
			})
		}
		
		function categoriesOnChange(){
			$("input:radio[name='categoryRoot']").on("change", function(){
				if ($(this).is(':checked')) {
		            var parentId = $(this).val();
		            $(this).html(getCategories(parentId));
		            
		        }
			});	
		}
		
		
		function categoryUpdate(){
			// alert("ASD");
			var itemId = $("#passItemId").val();
			$("input:radio[name='categoryToPost']").on("change", function(){
				alert("ASD");
					// var categoryId = $(this).val();			
					// $.ajax({ 
					    // type: 'POST', 
					    // url: 'controllers/changeItemCategory.php', 
					    // data: {itemId: itemId, categoryId : categoryId},
					    // beforeSend: function(){
					    	// // $("#progress").show();
					    // },
					    // complete: function(){
					    	// // $("#progress").hide();
					    // },
					    // error: function (data) {
					    	// alert("error");
					    // },
					    // success: function (data) {
					    	// alert("success");
						// },
					// })	
				});
		}
		
		
		$( document ).ready(function() {
			
			getPhotos();
			selectSize();
			selectSizeStartup();
			categoryUpdate();
			// categoriesOnChange();
			getItemCategory();
		});
	</script>
		
		
	</head>
	
	<body>
		
		<div id="siteContainer">
			
			<a href="../shop.php"><div>GO TO SHOP</div></a>
			
			<div id="header"><div id="title">ASANTI CMS</div><div id="subtitle">Edytuj przedmiot</div></div>
			
			
			<div id="container">
				
				<div id="leftMenu">
					<!-- Include links ---------------------------------------------------------- -->
					<?php include 'include/leftMenu.php'; ?>
					<!-- ------------------------------------------------------------------------ -->
				</div>
				
				<div id="rightContent">
					
					<div id="container">
						
						<div id="editItem">
							
							<form action=""; method="POST" enctype="multipart/form-data">
								
								<div id="title">
									<div class="label">Nazwa przedmiotu:</div>
									<input class="nameInput" name="name" type="text" value="<?php echo($itemTitle); ?>"/>
								</div>
								
								<div id="photos">
									<div id="container">
										<div class="label">Zdjęcia przedmiotu:</div>
										<div id="headPhoto">
											
												<a href="pickHeadPhoto.php?itemId=<?php echo($itemId); ?>" ><div id="overlay">Zmień</br>zdjęcie</br>główne</div></a>
												<img src="<?php echo($headPhotoUrl); ?>" class="headPhoto"/>
		
											<a href="addPhotos.php?itemId=<?php echo($itemId); ?>" >
												<input type="button" value="+ Dodaj więcej zdjęć" id="addMorePhotosButton"/>
											</a>
											
										</div>
										
										<div id="thumbnails">
											
											<div class="container">
												
												<ul class="thumbs noscript">
													
												</ul>
												
											</div>
											
										</div>
									</div>
									
									
								</div>
								<div id="description">
								
									<div class="label">Opis przedmiotu:</div>
									
			        				<p>     
			                			<textarea name="description" cols="50" rows="15"><?php echo($itemDescription); ?></textarea>
			        				</p>
			        				
			        			</div>
								
								<div id="price">
		        				
			        				<div class="label">Cena przedmiotu</div>
			        				
			        				<input id="price" name="price" type="text" value="<?php echo($itemPrice); ?>"/> zł.
			        				
			        			</div>
		        			
		        			
		        				<div id="size">
		        				
			        				<div class="label">Wybierz rozmiar</div>
			        				
			        				<select id="options">
			        					<option value="height">Wzrost</option>
			        					<option value="foot">Długość stopy</option>
			        					<option value="head">Obwód głowy</option>
			        				</select>
			        				
			        				<div id="sizes">
			        					
			        				</div>
			        				
			        			</div>
		        			
		        			
			        			<div id="category">
			        				
			        				<div class="label">Wybierz kategorię</div>
			        				
			        				<!-- <div id="categoryLevel_0"></div>
			        				
			        				<div id="categoryLevel_2"></div> -->
			        				
			        			</div>
							
							
								<input type="submit" name="submit" value="Dalej" />
							        
							        
								<input type="hidden" id="passItemId" name="passItemId" value="<?php echo($itemId); ?>" />
							       
						            
							        
							</form>
							
							
						</div>	
					</div>			
				</div>
			</div>	
			<!-- Include footer --------------------------------------------------------- -->
			<?php include 'include/footer.php'; ?>
			<!-- ------------------------------------------------------------------------ -->
		</div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	</body>
</html>


<?php 



if(isset($_POST["submit"])){

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$itemId = $_POST['passItemId'];
$categoryId = $_POST['categoryToPost'];
$itemName = $_POST['name'];
$itemDescription = $_POST['description'];
$itemPrice = $_POST['price'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	echo("item id: " . $itemId . "   nazwa: " . $itemName . "   cena: " . $itemPrice . "  opis: " . $itemDescription);


	mysqli_query($conn,'UPDATE item SET name = "' . $itemName . '", description = "' . $itemDescription . '", price = "' . $itemPrice . '" WHERE id = "' . $itemId . '"');
	mysqli_query($conn,"UPDATE category_con SET cat_id = $categoryId WHERE item_id = $itemId");



mysqli_close($conn);

header("Location: editItem.php?itemId=" . $itemId);

}



?>