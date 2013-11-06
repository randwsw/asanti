<?php
$sText = 'sweterki, bluzeczki, płaszczyki';
$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
echo str_replace($aWhat, $aOn, $sText);
?>

















<?php

if(!isset($_POST["submit"])){?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	<link rel="stylesheet" href="../css/cms.css" type="text/css" />
	<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
	<?php include "../include/links.php"; ?>


	<script type="text/javascript">
	
	function getItems(){
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/getItems.php', 
		    data: {},
		    dataType: 'json',
		    error: function (data) {
		    	// alert("error");
		    },
		    success: function (data) { 
		    	$("#itemsTable").append("<tr class='itemsTableTrHeader'><td class='itemsTableTdNameHeader'>Nazwa przedmiotu</td>"
		    	+ "<td class='itemsTableTdPriceHeader'>Cena</td>"
		    	+ "<td class='itemsTableTdCategoryHeader'>Kategoria</td>"
		    	+ "<td class='itemsTableTdOptionsHeader'>Aktywny</td>");

		    	var j = 0;
		    	$.each(data,function(i,row){
		    		
		    		if(row.itemActive == 1){
		    			var checked = "checked";
		    		}else{
		    			var checked = "";
		    		}
		    		
		    		if(j%2 == 0){
		    			var trClass = "1";
		    		}else{
		    			var trClass = "2";
		    		}
		    		
		    		$("#itemsTable").append(
		    			"<tr class='itemsTableTr" + trClass + "'><td class='itemsTableTdName' id='nameTd" + row.itemId + "'>" + row.itemName 
		    			+ "</td><td class='itemsTableTdPrice' id='priceTd" + row.itemId + "'>" + row.itemPrice + " zł" 
		    			+ "</td><td class='itemsTableTdCategory' id='categoryTd" + row.itemId + "'>" + row.categoryName
		    			+ "</td><td class='itemsTableTdOptions'>"
		    			+ "<input type='button' class='editItemButton' value='Edytuj' id='editButton" + row.itemId + "'/>"
		    			+ "<input type='button' class='deleteItemButton' value='Usuń' id='deleteButton" + row.itemId + "'/>"
		    			// + "<input type='checkbox' class='itemActiveCheckbox' id='active" + row.ItemId + "'checked='" + checked + "'/></td></tr>"
		    			+ "<div class='squaredOne'><input type='checkbox' value='None' " + checked + " 'id='squaredOne" + row.itemId + "' name='check' />"
						+ "<label for='squaredOne" + row.itemId + "'></label></div></td></tr>"
		    		);
		    		j++;

		    	})
		    	deleteItem();
			},
		})
	}
	
	
	// function deleteButtonClick(){
		// $(".deleteItemButtons").click(function(){
				// $("#popupMessageBackground").css({"visibility" : "visible"});
				// $("#popupMessage").css({"visibility" : "visible"});
				// $("#popupMessageButtons").css({"visibility" : "visible"});
// 				
				// var itemId = $(this).attr("id");
				// itemId = itemId.substr(12,5);
// 				
				// var itemName = $("#nameTd" + itemId).html();
				// $("#popupMessageItemName").html(itemName);
				// cancelButtonClick();
				// deleteItem(itemId);
		// })
	// }
	
	
	function deleteItem(){
		$(".deleteItemButton").click(function(){
			if(!window.confirm("Na pewno chcesz usunąć ten przedmiot?")){
	            return false;
	        }else{
			var itemId = $(this).attr("id");
			itemId = itemId.substr(12,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/deleteItemController.php', 
			    data: {itemToDelete: itemId},
			    timeout: 50000,
			    beforeSend: function(){
			    	$("#progress").show();
			    },
			    complete: function(){
			    	$("#progress").hide();
			    },
			    error: function (data) {
			    	alert("ajaxError");
			    },
			    success: function (data) {
			    	// alert("ajaxSuccess");
			    	// alert(data);
			    	hidePopupMessage();
			    	$("#itemsTable").html("");
			    	getItems();
				},
			})
		}
		});
	}
	
	
	function changeActiveItem(){
		var active;
		$(".squaredOneCheckbox").change(function(){
			if($(this).is(':checked')){
				active = 1;
			}else{
				active = 0;
			}
		});
			var itemId = $(this).attr("id");
			itemId = itemId.substr(10,5);
			alert(itemId);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/itemActiveController.php', 
			    data: {itemId: itemId, active: active},
			    timeout: 50000,
			    beforeSend: function(){
			    	$("#progress").show();
			    },
			    complete: function(){
			    	$("#progress").hide();
			    },
			    error: function (data) {
			    	alert("ajaxError");
			    },
			    success: function (data) {
				},
			})
	}
	
	
	function cancelButtonClick(){
		$("#popupMessageCancelButton").click(function(){
			hidePopupMessage();
		})
	}
	
	function hidePopupMessage(){
		$("#popupMessageBackground").css({"visibility" : "hidden"});
		$("#popupMessage").css({"visibility" : "hidden"});
		$("#popupMessageButtons").css({"visibility" : "hidden"});
	}
	
	
	$(document).ready(function(){
		$("#progress").hide();
		getItems();
		// editClick();
		
	})
	</script>
</head>




<body>
	<!-- <div id="popupMessageBackground"></div>
	<div id="popupMessage">
		<div id="popupMessageTitle">Czy na pewno chcesz usunąć przedmiot?</div>
		<div id="popupMessageItemName"></div>
		<input type="hidden" id="popupMessageHidden" value="" />
	</div>
	<div id="popupMessageButtons">
		<input type="button" class="popupMessageButton" id="popupMessageDeleteButton" value="Usuń" />
		<input type="button" class="popupMessageButton" id="popupMessageCancelButton" value="Anuluj" />
	</div> -->
	<div id="container">
		<div id="header"><div id="cmsTitle">ASANTI CMS</div><div id="cmsSubTitle">Dodaj zdjęcia</div></div>
		<div id="content">
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			<div id="rightContent">
				<div id="rightContentContainer">
					<!-- <div class='squaredOne'><input type='checkbox' value='None' id='squaredOne' name='check' />
					<label for='squaredOne'></label></div> -->
					<img src="../img/progress_indicator.gif" id="progress" />
					<table id="itemsTable">
						<tr class="itemsTableTr">
							<td class="itemsTableTd"></td><td class="itemsTableTd"></td><td class="itemsTableTd"></td>
						</tr>
					</table>
				</div>				
			</div>
		</div>	
		<div id="cmsFooter">ASANTI CMS FOOTER</div>
	</div>
</body>
	
	
	
	
	
	
	
	
	
		
</html>

<?php }
else 
{
			
			
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn2=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn3=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn4=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$name = mysql_real_escape_string($_POST['newItemName']);
$description = mysql_real_escape_string($_POST['newItemDescription']);
$headPhotoId = 0;
$category = mysql_real_escape_string($_POST['categoryToPost']);
$price = mysql_real_escape_string($_POST['newItemPrice']);
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
  	mysqli_close($conn);
	if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		$result = mysqli_query($conn2,"SELECT id FROM item ORDER BY id DESC LIMIT 1 ");
		while($row2 = mysqli_fetch_array($result))
		  {
				$lastId = $row2['id'];
		  }
		  
	mysqli_close($conn2);
	
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
		if (!mysqli_query($conn3,$sql2))
		{
	  		die('Error: ' . mysqli_error($conn3));
	  		// mysqli_close($conn3);
	  	}else
	  	{
			// mysqli_close($conn3);
	  	}
	}
	mysqli_close($conn3);
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
		if (!mysqli_query($conn4,$sql3))
		{
	  		die('Error: ' . mysqli_error($conn4));
	  		// mysqli_close($conn3);
	  	}else
	  	{
			// mysqli_close($conn3);
	  	}
	mysqli_close($conn4);
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
	
	$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
	
	if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$i=1;
	foreach($filesList as $file){
		$sql = ("INSERT INTO photo (item_id, url) 
			VALUES ('" . $lastId . "', 'http://serwer1309748.home.pl/asanti/img/items/" . $lastId . "/" . $file . "')");
	if (!mysqli_query($conn,$sql))
  	{
  		die('Error: ' . mysqli_error($conn));
  	} else {
  		echo $i . " record added </br>";
		$i++;
  	}
	
	}
	
	mysqli_close($conn);
	// header("Location: pickHeadPhoto.php?itemId=" . $_GET['itemId']);
}
?>