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
	
	
	
	function getPromoted(){
		
		var category = getURLParameter("category");
		var sortBy = getURLParameter("sortBy");
		var direction = getURLParameter("direction");
		// alert(category + "  " + sort + "  " + direction);
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/promotedController.php', 
		    data: {action : "getAll", category : category, sortBy : sortBy, direction : direction},
		    dataType: 'json',
		    error: function (data) {
		    	// alert("error");
		    },
		    success: function (data) { 
		    	$("#promotedTable").html("<tr class='header'><td class='name'>Nazwa przedmiotu</td>"
		    	+ "<td class='category'>Kategoria</td>"
		    	+ "<td class='price'>Cena</td>"
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
		    		
		    		$("#promotedTable").append(
		    			"<tr class='" + trClass + "'><td class='name' id='nameTd" + row.itemId + "'>" + row.itemName 
		    			+ "</td><td class='category' id='categoryTd" + row.itemId + "'>" + row.categoryName
		    			+ "</td><td class='price' id='priceTd" + row.itemId + "'><input type='text' id='recPrice_" + row.recId + "'value='" + row.recPrice + "' /> zł <input type='button' class='changePrice' id='recPrice_" + row.recId + "' value='Zmień cenę' />"
		    			+ "</td><td class='options'>"
		    			+ "<input type='button' class='deleteAll' value='Usuń' id='deleteButton_" + row.itemId + "'/>"
						+ "</td></tr>"
		    		);
		    		j++;

		    	})
		    	deletePromoted();
		    	filter();
		    	sort();
		    	changePrice();
			},
		})
	}
	
	
	function getItems(){
		
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/itemController.php', 
		    data: {action : "getAll", promoted : "promoted"},
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
		    			+ "<input type='button' class='add' value='Dodaj do polecanych' id='addButton" + row.itemId + "'/>"
						+ "</td></tr>"
		    		);
		    		j++;

		    	})
		    	addButton();
			},
		})
	}
	
	function addButton(){
		$("input.add").click(function(){
			
			var itemId2 = $(this).attr("id");
			var itemId2 = itemId2.substr(9,5);

			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/promotedController.php', 
			    data: {action : "add", itemId2: itemId2},
			    timeout: 50000,
			    beforeSend: function(){
			    	$("#progress").show();
			    },
			    complete: function(){
			    	$("#progress").hide();
			    },
			    error: function (data) {
			    	// alert("ajaxError");
			    },
			    success: function (data) {
			    	window.location.replace("promoted.php");
				},
			})
			
			
		})
	}
	
	function changePrice(){
		$("input.changePrice").click(function(){
			
			var recId = $(this).attr("id").substr(9,5);
			var price = $("input[type='text']#recPrice_" + recId).val();

			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/promotedController.php', 
			    data: {action : "changePrice", recId : recId, price : price},
			    timeout: 50000,
			    beforeSend: function(){
			    	$("#progress").show();
			    },
			    complete: function(){
			    	$("#progress").hide();
			    },
			    error: function (data) {
			    	// alert("ajaxError");
			    },
			    success: function (data) {
			    	$("#confirmAlert").fadeIn("fast");
					$("#confirmAlert").delay(800).fadeOut(800);
				},
			})
			
			
		})
	}
	
	function deletePromoted(){
		$("input.deleteAll").click(function(){
			if(!window.confirm("Na pewno chcesz usunąć z polecanych?")){
	            return false;
	        }else{
	        	
			var itemId2 = $(this).attr("id");
			itemId2 = itemId2.substr(13,5);
			// alert(itemId1 + "  " + itemId2);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/promotedController.php', 
			    data: {action : "deleteAll", itemId2: itemId2},
			    timeout: 50000,
			    beforeSend: function(){
			    	$("#progress").show();
			    },
			    complete: function(){
			    	$("#progress").hide();
			    },
			    error: function (data) {
			    	// alert("ajaxError");
			    },
			    success: function (data) {
			    	window.location.replace("promoted.php");
				},
			})
		}
		});
	}
	
	
	
	function filter(){
		// var val = "2";
		// $("select#filter option").filter(function() {
		    // return $(this).val() == val; 
		// }).prop('selected', true);
		
		$("select#categoryFilter").on("change", function(){
			var sortByParam = getURLParameter("sortBy");
			var directionParam = getURLParameter("direction");
			var id = $(this).find("option:selected").attr('value');
			if(sortByParam != null && directionParam != null){
				var filter = ("sortBy=" + sortByParam + "&direction=" + directionParam);
				if(id=="all"){
					window.location.replace("promoted.php");
				}else{
					window.location.replace("promoted.php?category=" + id + "&" + filter);
				}
			}else{
				var filter = "";
				if(id=="all"){
					window.location.replace("promoted.php");
				}else{
					window.location.replace("promoted.php?category=" + id);
				}
			}
			
			
			
		})
	}
	
	
	function sort(){
		$("select#sort").on("change", function(){
			var direction = $(this).find("option:selected").attr('value');
			if(direction.substr(0,4) == "ASC_"){
				direction = "ASC";
			}else{
				if(direction.substr(0,4) == "DESC"){
					direction = "DESC";
				}
			}
			var sortBy = $(this).find("option:selected").attr('value').substr(5,20);
			var category = getURLParameter("category");
			if(category != null){
				window.location.replace("promoted.php?category=" + category + "&sortBy=" + sortBy + "&direction=" + direction);
			}else{
				window.location.replace("promoted.php?sortBy=" + sortBy + "&direction=" + direction);
			}
		})
	}
	
	function setSelects(){		
		var filterParam = getURLParameter("category");
		var sortParam = getURLParameter("sortBy");
		var directionParam = getURLParameter("direction");

		if(filterParam == null){
			$("select#categoryFilter").val("all");
		}else{
			$("select#categoryFilter").val(filterParam);
		}
		
		if(directionParam == "ASC"){
			directionParam = "ASC__";
		}else{
			if(directionParam == "DESC"){
				directionParam = "DESC_";
			}
		}
		if(sortParam == null){
			$("select#sort").val("ASC__itemName");
		}else{
			$("select#sort").val(directionParam + sortParam);
		}
		
	}
	
	
	$(document).ready(function(){
		$("#progress").hide();
		$("#confirmAlert").hide();
		sort();
		getPromoted();
		setSelects();
		// sort();
		getItems();
		deleteSet();
		addMore();
	})
	</script>
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header"><div id="title">ASANTI CMS</div><div id="subTitle">Przedmioty polecane</div></div>
		
		<div id="container">
			
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			
			
			<div id="rightContent">
				
				<div id="container">
					
					<!-- <img src="../img/progress_indicator.gif" id="progress" /> -->
					
					<div id="promoted">
						
						<div id="container">
							<div id="confirmAlert">Zmieniono cenę.</div>
							<?php
							if((!isset($_GET['action'])) || ($_GET['action'] != "add") && ($_GET['action'] != "edit")){
								echo('<div id="filters">
									<div class="filter">
										kategoria
										<select id="categoryFilter">
											<option value="all">wszystkie</option>');
											
											
											require_once("../include/config.php");
											$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
											$catList = array();
											
											$sql = "SET NAMES 'utf8'";
											!mysqli_query($conn,$sql);
											
											
											if (mysqli_connect_errno())
													  {
													  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
													  }
													
											$result = mysqli_query($conn,"SELECT id, parentId, name, urlName FROM category WHERE name != 'root' GROUP BY name ORDER BY catLevel");
													
											while($row1 = mysqli_fetch_array($result))
												{
													$parentId = $row1['parentId'];
													$result2 = mysqli_query($conn, "SELECT name FROM category WHERE id = '$parentId'");
													while($row2 = mysqli_fetch_array($result2))
														{
															if($row2['name'] == "root"){
																echo('<option value="' . $row1['id'] . '">' . $row1['name'] . '</option>');
															}else{
																echo('<option value="' . $row1['id'] . '">' . $row2['name'] . '  |  ' . $row1['name'] . '</option>');
															}
															
														}
													
												}
													
															
											mysqli_close($conn);
											
											
										echo('</select>
										Sortuj wg:
										<select id="sort">
											<option value="ASC__itemName">Nazwy a-z</option>
											<option value="DESC_itemName">Nazwy z-a</option>
											<option value="ASC__categoryName">Kategorii a-z</option>
											<option value="DESC_categoryName">Kategorii z-a</option>
										</select>
									</div>
								</div>');
							}
							?>
							<?php
							
							if(!isset($_GET['action'])){
								// echo('<a href="sets.php?action=add"><input type="button" value="Dodaj powiązanie"></button></a>');
									echo('<table id="promotedTable"></table>');
									echo("<a href='promoted.php?action=add'><input type='button' class='addMore' value='Dodaj więcej'/></a>");
							}
							
							?>
							
							
							
							
							
							
							
							<?php
							
							if(isset($_GET['action']) && $_GET['action'] == "add"){
								echo('<table id="itemsTable"></table></a href="promoted.php"><input type="button" name="goBack" value="Wróć" /></a>');
							}		
							?>
							
							
							
							
							
							
							<?php
							
							if(isset($_GET['action']) && $_GET['action'] == "edit"){
								
								require_once("../include/config.php");
								$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
								
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
											<div id="promoted">
												<div class="label">Powiązane przedmioty:</div>');
											
										$result2 = mysqli_query($conn,"SELECT i.name AS itemName, i.id AS itemId 
																FROM item i 
																WHERE i.id = (SELECT ic.item1_id FROM item_conn ic WHERE ic.item2_id = $itemId AND i.id = ic.item1_id)
																OR i.id = (SELECT ic.item2_id FROM item_conn ic WHERE ic.item1_id = $itemId AND i.id = ic.item2_id)"
																);
										
										while($row2 = mysqli_fetch_array($result2))
											{
												echo('<div class="row">');
												echo("<input type='button' class='delete' value='Usuń' id='deleteButton_" . $row2['itemId'] . "'/>");
												echo ('<p class="title">' . $row2["itemName"] . '</p>');
												
												echo('</div>');
											}
											echo('<div class="row">
													<a href="promoted.php?action=add&itemId=' . $itemId . '">
														<input type="button" class="addMore" value="Dodaj nowy" id="addMoreButton_' . $itemId . '"/>
													</a>
												</div>');
										echo('</div></div><a href="promoted.php"><input type="button" name="goBack" value="Wróć" /></a>');	
												
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
