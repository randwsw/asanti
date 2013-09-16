<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
	<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
	<?php include "../include/links.php"; ?>


	<script type="text/javascript">
	
	function getSets(){
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/getSets.php', 
		    data: {},
		    dataType: 'json',
		    error: function (data) {
		    	alert("error");
		    },
		    success: function (data) { 
		    	$("#setsTable").append("<tr class='header'><td class='name'>Nazwa przedmiotu</td>"
		    	+ "<td class='category'>Kategoria</td>"
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
		    		
		    		$("#setsTable").append(
		    			"<tr class='" + trClass + "'><td class='name' id='nameTd" + row.itemId + "'>" + row.itemName 
		    			+ "</td><td class='category' id='categoryTd" + row.itemId + "'>" + row.categoryName
		    			+ "</td><td class='options'>"
		    			+ "<input type='button' class='edit' value='Edytuj' id='editButton" + row.itemId + "'/>"
		    			+ "<input type='button' class='delete' value='Usuń komplet' id='deleteButton" + row.itemId + "'/>"
						+ "</td></tr>"
		    		);
		    		j++;

		    	})
		    	// addButton();
		    	// deleteItem();
		    	// changeActiveItem();
			},
		})
	}
	
	
	function getItems(){
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/getItems.php', 
		    data: {sets: "sets"},
		    dataType: 'json',
		    error: function (data) {
		    	// alert("error");
		    },
		    success: function (data) { 
		    	$("#itemsTable").append("<tr class='header'><td class='name'>Nazwa przedmiotu</td>"
		    	+ "<td class='category'>Kategoria</td>"
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
		    		
		    		$("#itemsTable").append(
		    			"<tr class='" + trClass + "'><td class='name' id='nameTd" + row.itemId + "'>" + row.itemName 
		    			+ "</td><td class='category' id='categoryTd" + row.itemId + "'>" + row.categoryName
		    			+ "</td><td class='options'>"
		    			+ "<input type='button' class='add' value='Dodaj jako komplet' id='addButton" + row.itemId + "'/>"
						+ "</td></tr>"
		    		);
		    		j++;

		    	})
		    	addButton();
		    	deleteItem();
		    	changeActiveItem();
			},
		})
	}
	
	function addButton(){
		$("input.add").click(function(){
			var itemId = $(this).attr("id");
			itemId2 = itemId.substr(9,5);
			
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/addSet.php', 
			    data: {itemId: itemId2},
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
			    	window.location.replace("sets.php");
				},
			})
			
			
		})
	}
	
	function deleteSet(){
		$("input.delete").click(function(){
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
	
	$(document).ready(function(){
		$("#progress").hide();
		getItems();
		getSets();
	})
	</script>
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../shop.php"><div>GO TO SHOP</div></a>
		
		<div id="header"><div id="title">ASANTI CMS</div><div id="subTitle">Przedmioty</div></div>
		
		<div id="container">
			
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			
			
			<div id="rightContent">
				
				<div id="container">
					
					<img src="../img/progress_indicator.gif" id="progress" />
					
					<div id="sets">
						
						<div id="container">
							<?php
							
							if(!isset($_GET['action'])){
								echo('<a href="sets.php?action=add"><input type="button" value="Dodaj komplet"></button></a>
									<table id="setsTable"></table>');
							}
							
							?>
							
							
							
							
							
							
							
							<?php
							
							if(isset($_GET['action']) && $_GET['action'] == "add"){
								echo('<a href="sets.php"><input type="button" value="Wróć" /></a><table id="itemsTable"></table>');
							}		
							?>
						</div>
						
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
