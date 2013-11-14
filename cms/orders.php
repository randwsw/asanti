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
	
	
	
	// function getOrders(category){
	function getOrders(){
		// var category = getURLParameter("category");
		// var sortBy = getURLParameter("sortBy");
		// var direction = getURLParameter("direction");
		// if(category=='all'){
			// category=null;
		// }
	
		// alert(category+" "+sortBy+" "+direction);
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/ordersController.php', 
		    // data: {action : "getAll", category : category, sortBy : sortBy, direction : direction, page : <?php $page = 1; if(isset($_GET['page'])) {$page =$_GET['page'];} echo($page);  ?>},
		    data: {action : "getAll"},
		    dataType: 'json',
		    error: function (data) {
		    	alert("error");
		    },
		    success: function (data) { 
		    	$("table#orders").html('<tr class="head">'+
									'<td id="order_id">ID</td>'+
									'<td id="item_name">Nazwa przedmiotu</td>'+
									// '<td id="price">cena za szt.</td>'+
									'<td id="quantity">il.</td>'+
									'<td id="shipping_price">cena przes.</td>'+
									'<td id="sum_price">cena razem</td>'+
									'<td id="date">data</td>'+
									'<td id="client_name">Nazwa klienta</td>'+
									'<td id="shipping">Przesyłka</td>'+
									'<td id="status">Status</td>'+
								'</tr>');

		    	var j = 0;
		    	$.each(data,function(i,row){
		    		
		    		var orderId = row.orderId;
		    		var itemName = "";
		    		var itemPrice = 0;
		    		var quantity = 0;
		    		var sumPrice = 0;
					
		    		$.ajax({ 
					    type: 'POST', 
					    url: 'controllers/ordersController.php', 
					    // data: {action : "getAll", category : category, sortBy : sortBy, direction : direction, page : <?php $page = 1; if(isset($_GET['page'])) {$page =$_GET['page'];} echo($page);  ?>},
					    data: {action : "getItems", orderId : orderId},
					    dataType: 'json',
					    error: function (data) {
					    	alert("error");
					    },
					    success: function (data) { 
					    	
					    	$.each(data,function(k,row2){
					    		
					    		if(itemName != ""){
					    			itemName += ("</br>" + row2.itemName);
					    		}else{
					    			itemName = row2.itemName;
					    		}
					    		
					    		quantity = parseInt(quantity) + parseInt(row2.quantity);
					    		
					    		itemPrice = parseInt(row2.itemPrice)*parseInt(row2.quantity);
					    		
					    		sumPrice = parseInt(sumPrice) + parseInt(itemPrice);
					    	});
					    	
					    	sumPrice = sumPrice + parseInt(row.shippingVal);
					    	sumPrice = sumPrice.toFixed(2);
					    	
					    	if(j%2 == 0){
				    			var trClass = "odd";
				    		}else{
				    			var trClass = "even";
				    		}
				    		
				    		var orderVal = parseInt(row.orderValue);
				    		
				    		var discount = parseInt(row.discount);
				    		
				    		var shippingVal = parseInt(row.shippingVal);
				    		
				    		if(discount != 0){
				    			var sum = (orderVal*(discount/100)+shippingVal);
				    		}else{
				    			var sum = (orderVal+shippingVal);
				    		}
				    		
				    		sum = sum.toFixed(2);
				    		
				    		var status = row.status;
		
				    		if(status == 0){
				    			var status2 = "<p style='color: rgba(191,21,21,0.8);'>nieopłacone</p>";
				    		}
				    		if(status == 1){
				    			var status2 = "<p style='color: #49a05e;'>w trakcie realizacji</p>";
				    		}
				    		if(status == 2){
				    			var status2 = "<p style='color: #49a05e;'>zrealizowane</p>";
				    		}
				    		if(status == 3){
				    			var status2 = "<p style='color: rgba(153,101,21,0.8);'>anulowane</p>";
				    		}
				    		
				    		$("table#orders").append('<tr class="' + trClass + '">'+
											'<td>' + row.orderId + '</td>'+
											'<td>' + itemName + '</td>'+
											// '<td>' + row.itemPrice + '</td>'+
											'<td>' + quantity + '</td>'+
											'<td>' + row.shippingVal + '</td>'+
											'<td>' + sumPrice + '</td>'+
											'<td>' + row.orderDate + '</td>'+
											'<td>' + row.userName + ' ' + row.userLastName + '</td>'+
											'<td>' + row.shipping + '</td>'+
											'<td class="status" id="order_' + row.orderId + '">' + status2 + '</td>'+
										'</tr>');
		
				    		j++;
				    		showDetails();
					    },
					   });
		    		
		    		
		    		
		    		

		    	});
		    	
		    	// editButton();
		    	// deleteItem();
		    	// changeActiveItem();
		    	// filter();
		    	// sort();
			},
		})
	}
	
	function showDetails(){
		$("table#orders td.status").click(function(){
			var id = $(this).attr("id").substr(6,10);
			
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/ordersController.php', 
			    data: {action : "getOne", orderId : id},
			    error: function (data) {
			    	alert("error");
			    },
			    success: function (data) { 
			    	$("div#det").html(data);
					$("#detailsPopup").show();
					$("div#grayout").css("visibility", "visible");
					closePopup();
				},
			});
			
		});	
	}
	
	function closePopup(){
		$("div#detailsPopup input[name='close']").click(function(){
			$("#detailsPopup").hide();
			$("div#grayout").css("visibility", "hidden");
		});
		// $("div#grayout").click(function(){
			// $("div#grayout").css("visibility", "hidden");
			// $("#detailsPopup").hide();
		// });
	}
	// function editButton(){
		// $("input.edit").click(function(){
			// var itemId = $(this).attr("id");
			// itemId2 = itemId.substr(10,5);
			// window.location.replace("editItem.php?itemId=" + itemId2);
		// })
	// }
	
	// function deleteItem(){
		// $("input.delete").click(function(){
			// if(!window.confirm("Na pewno chcesz usunąć ten przedmiot?")){
	            // return false;
	        // }else{
			// var itemId = $(this).attr("id");
			// itemId = itemId.substr(12,5);
			// $.ajax({ 
			    // type: 'POST', 
			    // url: 'controllers/itemController.php', 
			    // data: {action : "delete", itemToDelete: itemId},
			    // timeout: 50000,
			    // beforeSend: function(){
			    	// $("#progress").show();
			    // },
			    // complete: function(){
			    	// $("#progress").hide();
			    // },
			    // error: function (data) {
			    	// // alert("ajaxError");
			    // },
			    // success: function (data) {
			    	// $("#itemsTable").html("");
			    	// getItems();
				// },
			// })
		// }
		// });
	// }
// 	
	
	// function changeActiveItem(){
		// var active;
		// $(".squaredOneCheckbox").change(function(){
			// if($(this).is(':checked')){
				// active = 1;
			// }else{
				// active = 0;
			// }
			// var itemId = $(this).attr("id");
			// itemId = itemId.substr(10,5);
			// $.ajax({ 
			    // type: 'POST', 
			    // url: 'controllers/itemController.php', 
			    // data: {action : "changeActive", itemId: itemId, active: active},
			    // beforeSend: function(){
			    	// // $("#progress").show();
			    // },
			    // complete: function(){
			    	// // $("#progress").hide();
			    // },
			    // error: function (data) {
			    // },
			    // success: function (data) {
				// },
			// })
		// });
			
			
	//}
	
	
	
	
	// function filter(){
		// // var val = "2";
		// // $("select#filter option").filter(function() {
		    // // return $(this).val() == val; 
		// // }).prop('selected', true);
// 		
		// $("select#categoryFilter").on("change", function(){
			// var sortByParam = getURLParameter("sortBy");
			// var directionParam = getURLParameter("direction");
			// var id = $(this).find("option:selected").attr('value');
			// if(sortByParam != null && directionParam != null){
				// var filter = ("sortBy=" + sortByParam + "&direction=" + directionParam);
				// if(id=="all"){
					// window.location.replace("items.php");
				// }else{
					// window.location.replace("items.php?category=" + id + "&" + filter);
				// }
			// }else{
				// var filter = "";
				// if(id=="all"){
					// window.location.replace("items.php");
				// }else{
					// window.location.replace("items.php?category=" + id);
				// }
			// }
// 			
// 			
// 			
		// })
	// }
	
// 	
	// function sort(){
		// $("select#sort").on("change", function(){
			// var direction = $(this).find("option:selected").attr('value');
			// var page = getURLParameter("page");
			// if(page==null){
				// page=1;
			// }
			// if(direction.substr(0,4) == "ASC_"){
				// direction = "ASC";
			// }else{
				// if(direction.substr(0,4) == "DESC"){
					// direction = "DESC";
				// }
			// }
			// var sortBy = $(this).find("option:selected").attr('value').substr(5,20);
			// var category = getURLParameter("category");
			// if(category != null){
				// window.location.replace("items.php?category=" + category + "&sortBy=" + sortBy + "&direction=" + direction+"&page="+page);
			// }else{
				// window.location.replace("items.php?sortBy=" + sortBy + "&direction=" + direction+"&page="+page);
			// }
		// })
	// }
	
	// function setSelects(){		
		// var filterParam = getURLParameter("category");
		// var sortParam = getURLParameter("sortBy");
		// var directionParam = getURLParameter("direction");
// 
		// if(filterParam == null){
			// $("select#categoryFilter").val("all");
		// }else{
			// $("select#categoryFilter").val(filterParam);
		// }
// 		
		// if(directionParam == "ASC"){
			// directionParam = "ASC__";
		// }else{
			// if(directionParam == "DESC"){
				// directionParam = "DESC_";
			// }
		// }
		// if(sortParam == null){
			// $("select#sort").val("ASC__itemName");
		// }else{
			// $("select#sort").val(directionParam + sortParam);
		// }
// 		
	// }
	

	
	$(document).ready(function(){
		$("#detailsPopup").hide();
		// $("#progress").hide();
		// sort();
		getOrders();
		
		// setSelects();
		// filter();
	})
	</script>
</head>




<body>
	
	<div id="siteContainer">
		<div id="grayout"></div>
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
					
					<div id="orders">
						
						<div id="container">
							<div id="filters">
								<div class="filter">
									kategoria
									<select id="categoryFilter">
										<option value="all">wszystkie</option>
										<?php
										
										// $conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
										// $catList = array();
// 										
										// $sql = "SET NAMES 'utf8'";
										// !mysqli_query($conn,$sql);
// 										
// 										
										// if (mysqli_connect_errno())
												  // {
												  	// echo "Failed to connect to MySQL: " . mysqli_connect_error();
												  // }
// 												
										// $result = mysqli_query($conn,"SELECT id, parentId, name, urlName FROM category WHERE name != 'root' GROUP BY name ORDER BY catLevel");
// 												
										// while($row1 = mysqli_fetch_array($result))
											// {
												// $parentId = $row1['parentId'];
												// $result2 = mysqli_query($conn, "SELECT name FROM category WHERE id = '$parentId'");
												// while($row2 = mysqli_fetch_array($result2))
													// {
														// if($row2['name'] == "root"){
															// echo('<option value="' . $row1['id'] . '">' . $row1['name'] . '</option>');
														// }else{
															// echo('<option value="' . $row1['id'] . '">' . $row2['name'] . '  |  ' . $row1['name'] . '</option>');
														// }
// 														
													// }
// 												
											// }
// 												
// 														
										// mysqli_close($conn);
										
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
									
									// $pages=1;
									// if(isset($_GET['sortBy'])){
										// $sortby=$_GET['sortBy'];
									// } else {
										// $sortby=null;
									// }
// 									
									// if(isset($_GET['direction'])){
										// $direction=$_GET['direction'];
									// } else {
										// $direction=null;
									// }
// 									
									// include 'controllers/getItemsCount.php';
// 									
									// $itemsPerPage = 16;
										// for ($i = 1; $i <= $count; $i++) {
										    // if($i%$itemsPerPage==0){
										    	// $pages++;
										    // }
										// }
										// $nextpage = $page+1;
										// $prevpage = $page+-1;
										// if(($page==1)||($page==$pages)){
											// $edge = 2;
										// } else if(($page==2)||($page==$pages-1)){
											// $edge = 2;
										// } else {
											// $edge = 0;
										// }
										// echo("<div class='pages'>"); 
									// if($page>1)
										// echo("<a href='items.php?page=$prevpage'&category=$ct&sortBy=$sortby&direction=$direction>&#171</a> ");
											// for ($i = $page-(2+$edge); $i <= $page+(2+$edge); $i++) {
												// if(($i>0)&&($i<=$pages))
												// {
												// echo("<a id='page_$i' href='items.php?page=$i&category=$ct&sortBy=$sortby&direction=$direction'>".$i."</a> ");
												// }
											// }
										// if($page<$pages)
										// echo("<a href='items.php?page=$nextpage&category=$ct&sortBy=$sortby&direction=$direction'>&#187</a> ");
									// echo("</div>"); 
									?>
								</div>
							</div>
							
							<table id="orders"></table>
							<div id="detailsPopup">
								<div class="label">Szczegóły zamówienia</div>
								<div id="det"></div>
								
							</div>
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
