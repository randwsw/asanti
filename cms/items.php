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
		    			+ "<div class='squaredOne'><input type='checkbox' value='None' " + checked + " class='squaredOneCheckbox' id='squaredOne" + row.itemId + "' name='check' />"
						+ "<label for='squaredOne" + row.itemId + "'></label></div></td></tr>"
		    		);
		    		j++;

		    	})
		    	editButton();
		    	deleteItem();
		    	changeActiveItem();
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
	
	
	function editButton(){
		$(".editItemButton").click(function(){
			var itemId = $(this).attr("id");
			itemId2 = itemId.substr(10,5);
			window.location.replace("pickHeadPhoto.php?itemId=" + itemId2);
		})
	}
	
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
			var itemId = $(this).attr("id");
			itemId = itemId.substr(10,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/itemActiveController.php', 
			    data: {itemId: itemId, active: active},
			    beforeSend: function(){
			    	// $("#progress").show();
			    },
			    complete: function(){
			    	// $("#progress").hide();
			    },
			    error: function (data) {
			    },
			    success: function (data) {
				},
			})
		});
			
			
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
		<a href="../shop.php"><div>GO TO SHOP</div></a>
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
