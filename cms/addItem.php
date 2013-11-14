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

if(!isset($_POST["submit"])){?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
	<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
	<?php include "../include/links.php"; ?>

<script type="text/javascript">
	tinymce.init({
			mode: 'textarea',
			selector:'textarea',
			height: 400,
			width: 1000,
			resize: false,
			plugins: [
                "advlist autolink lists link image textcolor"
            ],
			toolbar: "newdocument | bold | italic | underline | strikethrough | alignleft | aligncenter | alignright | alignjustify | styleselect | formatselect | fontselect | fontsizeselect | forecolor | bullist | numlist | outdent | indent | blockquote | undo redo | removeformat | subscript | superscript",
			content_css: '../css/fonts.css,../css/content.css',
			// font_size : "8pt,10pt,12pt,14pt,16pt,18pt,20pt,24pt,32pt,36pt",
			font_formats: "OpenSans=OpenSansRegular;"+
			"Gabriola=Gabriola;"+
			"Andale Mono=andale mono,times;"+
	        "Arial=arial,helvetica,sans-serif;"+
	        "Arial Black=arial black,avant garde;"+
	        "Book Antiqua=book antiqua,palatino;"+
	        "Courier New=courier new,courier;"+
	        "Georgia=georgia,palatino;"+
	        "Helvetica=helvetica;"+
	        "Impact=impact,chicago;"+
	        "Tahoma=tahoma,arial,helvetica,sans-serif;"+
	        "Terminal=terminal,monaco;"+
	        "Times New Roman=times new roman,times;"+
	        "Trebuchet MS=trebuchet ms,geneva;"+
	        "Verdana=verdana,geneva;"+
	        "Webdings=webdings;"+
	        "Wingdings=wingdings,zapf dingbats",
		});
	
</script>
  
<script type="text/javascript">
		
		function addItem(){
			$('input[name="submit"]').click(function(){
				var name = $('input[name="name"]').val();
				var description = $('input[name="description"]').val();
				var category = $('input[name="category"]').val();
				var price = $('input[name="price"]').val();
				$.ajax({ 
					type: 'POST', 
					url: 'controllers/sizeController.php', 
					data: {action : "addItem", name : name, desctiption : description, category : category, price : price},
						 
					error: function (data) {
						// alert("porażka!");
					},
					success: function (data) {
						// alert("success");
					// alert(data);
					},
				});
			});
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
		
		
		function selectSizeStartup(){
			var sizeOf="wzrost";
			$.ajax({ 
								url: "controllers/sizeController.php",
								type: "POST",
								data:  {action : "getAll", sizeOf: sizeOf},
								cache: false
								}).done(function(data) {
						  		$("div#newItem #size #sizes").html(data);
							});
		}
		
		
		function selectSize(){
			$("select#options").on("change", function(){
				$("select#options option:selected").each(function () {
					var sizeOf = $(this).attr("value");
					$.ajax({ // loading classes list
								url: "controllers/sizeController.php",
								type: "POST",
								data:  {action : "getAll", sizeOf: sizeOf},
								cache: false
								}).done(function(data) {
						  		$("div#newItem #size #sizes").html(data);
							});
				});
			});
		}
		
		function getCategories(parentId){
			$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/categoryController.php', 
		    data: {action : "getCategories", parentId: parentId},
		    error: function (data) {
		    	// alert("error");
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
				// var parentId = $(this).val();
				// alert(parentId);
				if ($(this).is(':checked')) {
		            var parentId = $(this).val();
		            $(this).html(getCategories(parentId));
		            // alert(parentId);
		        }
				
				
				// $("#categoryLevel_0 option:selected").each(function () {
					// var parentId = $(this).val();
					// $(this).html(getCategories(parentId));
				// });
			});	
		}
		
		$(document).ready(function(){
			showPreview();
			// selectSize();
			// selectSizeStartup();
			getCategories(0);
			categoriesOnChange();
		});
		
		</script>
  
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header"><div id="title">ASANTI CMS</div><div id="subTitle">Dodaj przedmiot</div></div>
		
		<div id="container">
			
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			
			<div id="rightContent">
				
				<div id="container">
					
					<input type="button" onclick="history.go(-1)" value="Powrót" />
					
					<div id="newItem">
						<form action="controllers/itemController.php"; method="POST" enctype="multipart/form-data">
							
							<div id="name">
								
								<div class="label">Nazwa przedmiotu:</div>
								
								<input id="name" name="name" type="text" value="" />
								
							</div>
							
							
							<div id="description">
								
								<div class="label">Opis przedmiotu:</div>
								
		        				<p>     
		                			<textarea name="description" cols="50" rows="15">Opis nowego przedmiotu.</textarea>
		        				</p>
		        				
		        			</div>
		        			
		        			
		        			<div id="price">
		        				
		        				<div class="label">Cena przedmiotu</div>
		        				
		        				<input id="price" name="price" type="text" value="0,00"/> zł.
		        				
		        			</div>
		        			
		        			
		        			<div id="size">
		        				
		        				<div class="label">Wybierz rozmiar</div>
		        				
		        				
		        				
		        				<div id="test">
		        					
		        					<?php

		
										require_once("../include/config.php");
							   			$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
										class size {
											public $id;
											public $value;
											public $sizeOf;
										}
										
										
										class sizeCat {
											public $name;
											public $sizeOf;
										}
										
										$sizeOfList = array();
																				
										// ENCODING TO UTF8
										$sql = "SET NAMES 'utf8'";
										!mysqli_query($conn,$sql);		
																				
										if (mysqli_connect_errno())
											{
												echo "Failed to connect to MySQL: " . mysqli_connect_error();
											}
																				
																				
																				
										$result1 = mysqli_query($conn,"SELECT name, sizeOf FROM size GROUP BY name");
																					
										while($row1 = mysqli_fetch_array($result1))
											{
												$sizeCat = new sizeCat;
												$sizeCat->name = $row1['name'];
												$sizeCat->sizeOf = $row1['sizeOf'];
												array_push($sizeOfList, $sizeCat);
											}
											
											
											
										foreach($sizeOfList as $sizeOf){
											$sizeList = array();
											$result = mysqli_query($conn,"SELECT * FROM size WHERE name = '$sizeOf->name' ORDER BY value");
																					
											while($row = mysqli_fetch_array($result))
												{
													$size = new size;
													$size->id = $row['id'];
													$size->value = $row['value'];
													$size->sizeOf = $row['sizeOf'];
													array_push($sizeList, $size);
												}
											
											echo("<div class='box'>
														<div class='sizeOf' id='sizeOf_$sizeOf->name'>
																$sizeOf->name
														</div>");
											foreach($sizeList as $s){

												echo("<input type='checkbox' class='sizeCheckbox' name='pickSize[]' value='" . $s->id . "'>" . $s->value . "cm</br>");
											}
											
											echo("</div>");
											unset($sizeList);
										}
										
										// mysqli_close($conn);
										
										
										?>
		        					
		        				</div>
		        				
		        				<!-- <select id="options">
		        					<option value="wzrost">Wzrost</option>
		        					<option value="dlugosc_stopy">Długość stopy</option>
		        					<option value="obwod_glowy">Obwód głowy</option>
		        				</select> -->
		        				
		        				<div id="sizes">
		        					
		        				</div>
		        				
		        			</div>
		        			
		        			<div id="colors">
		        				<div class="label">Wybierz kolor</div>
		        				<?php
										
										$result2 = mysqli_query($conn,"SELECT * FROM colors GROUP BY name");
																					
										while($row2 = mysqli_fetch_array($result2))
											{
												$id = $row2['id'];
												$name = $row2['name'];
												echo("<input type='checkbox' name='pickColor[]' value='$id' /> $name </br>");
											}
										
										mysqli_close($conn);
		        				
		        				?>
		        				
		        			</div>
		        			
		        			<div id="category">
		        				
		        				<div class="label">Wybierz kategorię</div>
		        				
		        				<div id="categoryLevel_0"></div>
		        				
		        				<div id="categoryLevel_2"></div>
		        				
		        			</div>
		        			
		        			
							<div id="photos">
								
								<div class="label">Wybierz zdjęcia:</div>
								
								<input name="userfile[]" id="image-input" type="file" multiple="multiple" accept="image/*">
								
								<div class="preview-area"></div>
								
							</div>
							
							<input type="hidden" name="action" value="addItem" />
							<input type="submit" name="submit" value="Dalej" />
							
						</form>
					
					</div>
					
				</div>	
							
			</div>
			
		</div>	
		
		
		<div id="footer">ASANTI CMS FOOTER</div>
		
	</div>
	
</body>
	
	
	
	
	
	
	
	
	
		
</html>

<?php }
else 
{

}
?>