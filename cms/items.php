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
	
	<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> -->
	<title>Asanti - cms</title>
	<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
	<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
	<?php include "../include/links.php"; ?>


	<script type="text/javascript">
	
	function getURLParameter(name) {
		    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
		}
	
	
	
	function getItems(category){
		var category = getURLParameter("category");
		var sortBy = getURLParameter("sortBy");
		var direction = getURLParameter("direction");
		if(category=='all'){
			category=null;
		}
	
		// alert(category+" "+sortBy+" "+direction);
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/itemController.php', 
		    data: {action : "getAll", category : category, sortBy : sortBy, direction : direction, page : <?php $page = 1; if(isset($_GET['page'])) {$page =$_GET['page'];} echo($page);  ?>},
		    dataType: 'json',
		    error: function (data) {
		    	// alert("error");
		    },
		    success: function (data) { 
		    	$("#itemsTable").html("<tr class='header'><td class='name'>Nazwa przedmiotu</td>"
		    	+ "<td class='price'>Cena</td>"
		    	+ "<td class='category'>Kategoria</td>"
		    	+ "<td class='options'>Aktywny</td>");

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
		    			+ "</td><td class='price' id='priceTd" + row.itemId + "'>" + row.itemPrice + " zł" 
		    			+ "</td><td class='category' id='categoryTd" + row.itemId + "'>" + row.categoryName
		    			+ "</td><td class='options'>"
		    			+ "<input type='button' class='edit' value='Edytuj' id='editButton" + row.itemId + "'/>"
		    			+ "<input type='button' class='delete' value='Usuń' id='deleteButton" + row.itemId + "'/>"
		    			// + "<input type='checkbox' class='itemActiveCheckbox' id='active" + row.ItemId + "'checked='" + checked + "'/></td></tr>"
		    			+ "<div class='squaredOne'><input type='checkbox' value='None' " + checked + " class='squaredOneCheckbox' id='squaredOne" + row.itemId + "' name='check' />"
						+ "<label for='squaredOne" + row.itemId + "'></label></div></td></tr>"
		    		);
		    		j++;

		    	})
		    	editButton();
		    	deleteItem();
		    	changeActiveItem();
		    	filter();
		    	sort();
			},
		})
	}
	
	function editButton(){
		$("input.edit").click(function(){
			var itemId = $(this).attr("id");
			itemId2 = itemId.substr(10,5);
			window.location.replace("editItem.php?itemId=" + itemId2);
		})
	}
	
	function deleteItem(){
		$("input.delete").click(function(){
			if(!window.confirm("Na pewno chcesz usunąć ten przedmiot?")){
	            return false;
	        }else{
			var itemId = $(this).attr("id");
			itemId = itemId.substr(12,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/itemController.php', 
			    data: {action : "delete", itemToDelete: itemId},
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
			    url: 'controllers/itemController.php', 
			    data: {action : "changeActive", itemId: itemId, active: active},
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
					window.location.replace("items.php");
				}else{
					window.location.replace("items.php?category=" + id + "&" + filter);
				}
			}else{
				var filter = "";
				if(id=="all"){
					window.location.replace("items.php");
				}else{
					window.location.replace("items.php?category=" + id);
				}
			}
			
			
			
		})
	}
	
	
	function sort(){
		$("select#sort").on("change", function(){
			var direction = $(this).find("option:selected").attr('value');
			var page = getURLParameter("page");
			if(page==null){
				page=1;
			}
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
				window.location.replace("items.php?category=" + category + "&sortBy=" + sortBy + "&direction=" + direction+"&page="+page);
			}else{
				window.location.replace("items.php?sortBy=" + sortBy + "&direction=" + direction+"&page="+page);
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
		sort();
		getItems();
		setSelects();
		filter();
	})
	</script>
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
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
					
					<div id="items">
						
						<div id="container">
							<div id="filters">
								<div class="filter">
									kategoria
									<select id="categoryFilter">
										<option value="all">wszystkie</option>
										<?php
										
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
										
										?>
									</select>
									Sortuj wg:
									<select id="sort">
										<option value="ASC__itemName">Nazwy a-z</option>
										<option value="DESC_itemName">Nazwy z-a</option>
										<option value="ASC__categoryName">Kategorii a-z</option>
										<option value="DESC_categoryName">Kategorii z-a</option>
										<option value="ASC__connections">Powiązań rosnąco</option>
										<option value="DESC_connections">Powiązań malejąco</option>
									</select>
									<?php
									
									$pages=1;
									if(isset($_GET['sortBy'])){
										$sortby=$_GET['sortBy'];
									} else {
										$sortby=null;
									}
									
									if(isset($_GET['direction'])){
										$direction=$_GET['direction'];
									} else {
										$direction=null;
									}
									
									include 'controllers/getItemsCount.php';
									
									$itemsPerPage = 16;
										for ($i = 1; $i <= $count; $i++) {
										    if($i%$itemsPerPage==0){
										    	$pages++;
										    }
										}
										$nextpage = $page+1;
										$prevpage = $page+-1;
										if(($page==1)||($page==$pages)){
											$edge = 2;
										} else if(($page==2)||($page==$pages-1)){
											$edge = 2;
										} else {
											$edge = 0;
										}
										echo("<div class='pages'>"); 
									if($page>1)
										echo("<a href='items.php?page=$prevpage'&category=$ct&sortBy=$sortby&direction=$direction>&#171</a> ");
											for ($i = $page-(2+$edge); $i <= $page+(2+$edge); $i++) {
												if(($i>0)&&($i<=$pages))
												{
												echo("<a id='page_$i' href='items.php?page=$i&category=$ct&sortBy=$sortby&direction=$direction'>".$i."</a> ");
												}
											}
										if($page<$pages)
										echo("<a href='items.php?page=$nextpage&category=$ct&sortBy=$sortby&direction=$direction'>&#187</a> ");
									echo("</div>"); 
									?>
								</div>
							</div>
							
							<table id="itemsTable"></table>
							
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
