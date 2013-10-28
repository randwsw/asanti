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
	<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
	<?php include "../include/links.php"; ?>

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
						<form action="pickHeadPhoto.php"; method="POST" enctype="multipart/form-data">
							
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

		
										$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");										
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
											$result = mysqli_query($conn,"SELECT * FROM size WHERE name = '$sizeOf->name'");
																					
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
										
										mysqli_close($conn);
										
										
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
			
			
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$name = $conn->real_escape_string($_POST['name']);
$name = $purifier->purify($name);

$description = $conn->real_escape_string($_POST['description']);
// $description = $purifier->purify($desciption);

$headPhotoId = 0;

$category = $conn->real_escape_string($_POST['categoryToPost']);
$category = $purifier->purify($category);

$price = $conn->real_escape_string($_POST['price']);
$price = $purifier->purify($price);


// //////////////////////////////////////////////////////////////////////////////////////////////////// //



// CREATE ITEM
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }




$sql="INSERT INTO item (name, description, headPhotoId, price)
VALUES
('$name','$description','$headPhotoId', '$price')";

if (!mysqli_query($conn,$sql))
  {
	  die('Error: ' . mysqli_error($conn));
	  mysqli_close($conn);
  }else
  {
  	// mysqli_close($conn);
	if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		$result = mysqli_query($conn,"SELECT id FROM item ORDER BY id DESC LIMIT 1 ");
		while($row2 = mysqli_fetch_array($result))
		  {
				$lastId = $row2['id'];
		  }
	
  }
  
  
  
  
// ADD SIZE_ITEM

if(!empty($_POST['pickSize'])) {
    foreach($_POST['pickSize'] as $check) {
    	
		if (mysqli_connect_errno())
	  	{
	  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  	}
		
		
		$sql2="INSERT INTO size_item (sizeId, itemId)
			VALUES
			('$check','$lastId')";
		if (!mysqli_query($conn,$sql2))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		// mysqli_close($conn3);
	  	}else
	  	{
			// mysqli_close($conn3);
	  	}
	}
}

  
  
    		
// ADD CATEGORY

if(!empty($category)) {
    
		if (mysqli_connect_errno())
	  	{
	  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  	}
		
		
		$sql3="INSERT INTO category_con (item_id, cat_id)
			VALUES
			('$lastId','$category')";
		if (!mysqli_query($conn,$sql3))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		// mysqli_close($conn3);
	  	}else
	  	{
			// mysqli_close($conn3);
	  	}
}			
			

			
		
	
	set_time_limit(300);//for uploading big files
	
	$paths="asanti/img/items/" . $lastId;

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
	$i="1";
	foreach($filesList as $file){
		$sql = ("INSERT INTO photo (name, item_id, url, orderN) 
			VALUES ('" . $file . "', '" . $lastId . "', 'http://serwer1309748.home.pl/asanti/img/items/" . $lastId . "/" . $file . "', '$i')");
			$i++;
	if (!mysqli_query($conn,$sql))
  	{
  		die('Error: ' . mysqli_error($conn));
  	} else {
  		// echo $i . " record added </br>";
		// $i++;
  	}
	
	}
	mysqli_close($conn);
	header("Location: pickHeadPhoto.php?itemId=" . $lastId);
}
?>