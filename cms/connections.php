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
		    	+ "<td class='category'>Powiązania</td>"
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
		    			+ "</td><td class='category' id='categoryTd" + row.itemId + "'>" + row.connections
		    			+ "</td><td class='options'>"
		    			+ "<a href='connections.php?action=add&itemId=" + row.itemId + "'><input type='button' class='addMore' value='Dodaj' id='addButton" + row.itemId + "'/></a>"
		    			+ "<a href='connections.php?action=edit&itemId=" + row.itemId + "'><input type='button' class='edit' value='Edytuj' id='editButton" + row.itemId + "'/></a>"
		    			+ "<input type='button' class='delete' value='Usuń' id='deleteButton" + row.itemId + "'/>"
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
		$.urlParam = function(name){
		    var results = new RegExp('[\\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);
		    return results[1] || 0;
		}
		var itemId = $.urlParam('itemId');
		
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/getItems.php', 
		    data: {sets: "sets", itemId: itemId},
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
		    			+ "<input type='button' class='add' value='Dodaj powiązanie' id='addButton" + row.itemId + "'/>"
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
			
			$.urlParam = function(name){
			    var results = new RegExp('[\\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);
			    return results[1] || 0;
			}
			var itemId1 =  $.urlParam('itemId');
			
			var itemId2 = $(this).attr("id");
			var itemId2 = itemId2.substr(9,5);

			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/addSet.php', 
			    data: {itemId1: itemId1, itemId2: itemId2},
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
			    	window.location.replace("connections.php?action=edit&itemId=" + itemId1);
				},
			})
			
			
		})
	}
	
	function deleteSet(){
		$("input.delete").click(function(){
			if(!window.confirm("Na pewno chcesz usunąć powiązanie?")){
	            return false;
	        }else{
			var itemId = $(this).attr("id");
			itemId = itemId.substr(12,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/deleteSet.php', 
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
	
	
	
	
	$(document).ready(function(){
		$("#progress").hide();
		getSets();
		getItems();
		
		addMore();
	})
	</script>
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../shop.php"><div>GO TO SHOP</div></a>
		
		<div id="header"><div id="title">ASANTI CMS</div><div id="subTitle">Przedmioty powiązane</div></div>
		
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
								// echo('<a href="sets.php?action=add"><input type="button" value="Dodaj powiązanie"></button></a>');
									echo('<table id="setsTable"></table>');
							}
							
							?>
							
							
							
							
							
							
							
							<?php
							
							if(isset($_GET['action']) && $_GET['action'] == "add"){
								echo('<a href="sets.php"><input type="button" value="Wróć" /></a><table id="itemsTable"></table>');
							}		
							?>
							
							
							
							
							
							
							<?php
							
							if(isset($_GET['action']) && $_GET['action'] == "edit"){
								
								$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
								$itemId = $_GET['itemId'];
								
								
								
								$sql = "SET NAMES 'utf8'";
								!mysqli_query($conn,$sql);
								
								
								if (mysqli_connect_errno())
										  {
										  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
										  }
										
										$result = mysqli_query($conn,"SELECT i.name AS itemName, i.id AS itemId FROM item i WHERE i.id = $itemId");
										
										while($row1 = mysqli_fetch_array($result))
											{
												$itemName = $row1['itemName'];
												$itemId = $row1['itemId'];
											}
											
										echo('<div id="connItem">');
										echo('<div id="title">
												<div class="label">Nazwa przedmiotu:</div>
												<p class="title">' . $itemName . '</p>
											</div>
											<div id="connections">
												<div class="label">Powiązane przedmioty:</div>');
											
										$result2 = mysqli_query($conn,"SELECT i.name AS itemName, i.id AS itemId 
																FROM item i 
																WHERE i.id = (SELECT ic.item1_id FROM item_conn ic WHERE ic.item2_id = $itemId AND i.id = ic.item1_id)
																OR i.id = (SELECT ic.item2_id FROM item_conn ic WHERE ic.item1_id = $itemId AND i.id = ic.item2_id)"
																);
										
										while($row2 = mysqli_fetch_array($result2))
											{
												echo('<div class="row">');
												echo("<input type='button' class='delete' value='Usuń' id='deleteButton" . $row2['itemId'] . "'/>");
												echo ('<p class="title">' . $row2["itemName"] . '</p>');
												
												echo('</div>');
											}
											echo('<div class="row">
													<a href="connections.php?action=add&itemId=' . $itemId . '">
														<input type="button" class="addMore" value="Dodaj nowy" id="addMoreButton_' . $itemId . '"/>
													</a>
												</div>');
										echo('</div></div>');	
												
									mysqli_close($conn);
									
									
									
								// echo('<a href="sets.php"><input type="button" value="Wróć" /></a><table id="itemsTable"></table>');								
								
								
								
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
