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
	
	
	
	function getUsers(){
		var category = getURLParameter("category");
		var sortBy = getURLParameter("sortBy");
		var direction = getURLParameter("direction");
		
		if(category=='all'){
			category=null;
		}
		
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/usersController.php', 
		    data: {action: "getAll", sortBy : sortBy, direction : direction, page : <?php $page = 1; if(isset($_GET['page'])) {$page =$_GET['page'];} echo($page);  ?>},
		    dataType: 'json',
		    error: function (data) {
		    	// alert("error");
		    },
		    success: function (data) { 
		    	
		    	$("#usersTable").html("<tr class='header'><td class='email'>Adres email</td>"
		    	+ "<td class='name'>Imię</td>"
		    	+ "<td class='lastName'>Nazwisko</td>"
		    	+ "<td class='address'>Adres</td>"
		    	+ "<td class='options'>Aktywny</td>");

		    	var j = 0;
		    	$.each(data,function(i,row){
		    		
		    		if(row.active == 1){
		    			var checked = "checked";
		    		}else{
		    			var checked = "";
		    		}
		    		
		    		if(j%2 == 0){
		    			var trClass = "odd";
		    		}else{
		    			var trClass = "even";
		    		}
		    		
		    		$("#usersTable").append(
		    			"<tr class='" + trClass + "'><td class='email' id='emailTd" + row.id + "'>" + row.email
		    			+ "</td><td class='name' id='nameTd" + row.id + "'>" + row.name  
		    			+ "</td><td class='lastName' id='lastNameTd" + row.id + "'>" + row.lastName
		    			+ "</td><td class='address' id='address" + row.id + "'>" + row.pcode + " " + row.street + " " + row.city
		    			+ "</td><td class='options'>"
		    			+ "<input type='button' class='edit' value='Edytuj' id='editButton" + row.id + "'/>"
		    			+ "<input type='button' class='delete' value='Usuń' id='deleteButton" + row.id + "'/>"
		    			// + "<input type='checkbox' class='itemActiveCheckbox' id='active" + row.ItemId + "'checked='" + checked + "'/></td></tr>"
		    			+ "<div class='squaredOne'><input type='checkbox' value='None' " + checked + " class='squaredOneCheckbox' id='squaredOne" + row.id + "' name='check' />"
						+ "<label for='squaredOne" + row.id + "'></label></div></td></tr>"
		    		);
		    		j++;

		    	})
		    	editButton();
		    	deleteUser();
		    	changeActiveUser();
		    	// filter();
		    	sort();
			},
		})
	}
	
	function editButton(){
		$("input.edit").click(function(){
			var userId = $(this).attr("id");
			userId = userId.substr(10,5);
			window.location.replace("users.php?action=edit&userId=" + userId);
		})
	}
	
	function deleteUser(){
		$("input.delete").click(function(){
			if(!window.confirm("Na pewno chcesz usunąć tego użytkownika?")){
	            return false;
	        }else{
			var userId = $(this).attr("id");
			userId = userId.substr(12,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/usersController.php', 
			    data: {action : "deleteUser", userId: userId},
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
			    	$("#usersTable").html("");
			    	getUsers();
				},
			})
		}
		});
	}
	
	
	function changeActiveUser(){
		var active;
		$(".squaredOneCheckbox").change(function(){
			if($(this).is(':checked')){
				active = 1;
			}else{
				active = 0;
			}
			var userId = $(this).attr("id");
			userId = userId.substr(10,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/usersController.php', 
			    data: {action: "changeActive", userId: userId, active: active},
			    beforeSend: function(){
			    	// $("#progress").show();
			    },
			    complete: function(){
			    	// $("#progress").hide();
			    },
			    error: function (data) {
			    },
			    success: function (data) {
			    	$("#confirmAlert").fadeIn("fast");
					$("#confirmAlert").delay(800).fadeOut(800);
				},
			})
		});
			
			
	}
	
	
	function updatePw(){
		$("input#updatePw").on("click", function(){
			var userId = $("input#userId").val();
			var newPw = $("input#newPw").val();
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/usersController.php', 
			    data: {action: "changePw", userId: userId, newPw : newPw},
			    beforeSend: function(){
			    	// $("#progress").show();
			    },
			    complete: function(){
			    	// $("#progress").hide();
			    },
			    error: function (data) {
			    },
			    success: function (data) {
			    	$("#confirmAlert").fadeIn("fast");
					$("#confirmAlert").delay(800).fadeOut(800);
					$("input#newPw").val("");
				},
			})
		});
		
	}
	
	
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
			// var category = getURLParameter("category");
			// if(category != null){
				// window.location.replace("users.php?category=" + category + "&sortBy=" + sortBy + "&direction=" + direction);
			// }else{
				window.location.replace("users.php?sortBy=" + sortBy + "&direction=" + direction);
			// }
		})
	}
	
	function setSelects(){		
		// var filterParam = getURLParameter("category");
		var sortParam = getURLParameter("sortBy");
		var directionParam = getURLParameter("direction");

		// if(filterParam == null){
			// $("select#categoryFilter").val("all");
		// }else{
			// $("select#categoryFilter").val(filterParam);
		// }
		
		if(directionParam == "ASC"){
			directionParam = "ASC__";
		}else{
			if(directionParam == "DESC"){
				directionParam = "DESC_";
			}
		}

		if(sortParam == null){
			$("select#sort").val("ASC__email");
		}else{
			$("select#sort").val(directionParam + sortParam);
		}
		
	}
	
	
	
	
	function updateUser(){
		$("input#update").on("click", function(){
			var userId = $("input#userId").val();
			var email = $('input[name="email"]').val();
			var name = $('input[name="name"]').val();
			var lastName = $('input[name="lastName"]').val();
			var pcode = $('input[name="pcode"]').val();
			var street = $('input[name="street"]').val();
			var city = $('input[name="city"]').val();
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/usersController.php', 
			    data: {action: "update", userId: userId, email : email, name : name, lastName : lastName, pcode : pcode, street : street, city : city},
			    beforeSend: function(){
			    	// $("#progress").show();
			    },
			    complete: function(){
			    	// $("#progress").hide();
			    },
			    error: function (data) {
			    },
			    success: function (data) {
			    	window.location.replace("users.php?action=edit&userId=" + userId);
				},
			})
		});
		
	}
	
	
	
	
	
	
	
	
	
	
	
	$(document).ready(function(){
		$("#confirmAlert").hide();
		updatePw();
		updateUser();
		$("#progress").hide();
		sort();
		getUsers();
		setSelects();
		filter();
		
	})
	</script>
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header"><div id="title">ASANTI CMS</div><div id="subTitle">Użytkownicy</div></div>
		
		<div id="container">
			
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			
			
			<div id="rightContent">
				
				<div id="container">
					
					<!-- <img src="../img/progress_indicator.gif" id="progress" /> -->
					
					<div id="users">
						
						<div id="container">
							<?php
								if(!isset($_GET['action'])){
									echo('<div id="filters">
										<div class="filter">
											Sortuj wg:
											<select id="sort">
												<option value="ASC__email">Adres email a-z</option>
												<option value="DESC_email">Adres email z-a</option>
												<option value="ASC__name">Imię a-z</option>
												<option value="DESC_name">Imię z-a</option>
												<option value="ASC__lastName">Nazwisko a-z</option>
												<option value="DESC_lastName">Nazwisko z-a</option>
											</select>');
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
											
											$itemsPerPage = 8;
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
												echo("<a href='users.php?page=$prevpage'&sortBy=$sortby&direction=$direction>&#171</a> ");
													for ($i = $page-(2+$edge); $i <= $page+(2+$edge); $i++) {
														if(($i>0)&&($i<=$pages))
														{
														echo("<a id='page_$i' href='users.php?page=$i&sortBy=$sortby&direction=$direction'>".$i."</a> ");
														}
													}
												if($page<$pages)
												echo("<a href='users.php?page=$nextpage&&sortBy=$sortby&direction=$direction'>&#187</a> ");
											echo("</div>"); 
										echo('</div>
									</div>
									<div id="confirmAlert">Zmieniono status użytkownika</div>
									<table id="usersTable"></table>');
								}
								
							?>
							<?php
								if(isset($_GET['action']) && $_GET['action'] == "edit"){
									
									// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
									$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
									$userId = $_GET['userId'];
									// //////////////////////////////////////////////////////////////////////////////////////////////////// //
									
									$sql = "SET NAMES 'utf8'";
									!mysqli_query($conn,$sql);
									
									
									if (mysqli_connect_errno())
											  {
											  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
											  }
									$result = mysqli_query($conn,"SELECT * FROM users u, address a WHERE u.id='$userId' AND u.id = a.user_id");

										
										while($row = mysqli_fetch_array($result))
											{
												$email = $row['email'];
												$name = $row['name'];
												$lastName = $row['lastName'];
												$pcode = $row['pcode'];
												$street = $row['street'];
												$city = $row['city'];
											}
									mysqli_close($conn);
											
									echo('<form action="users.php?action=edit&userId=' . $userId . '" method="POST" enctype="multipart/form-data">
										<div id="confirmAlert">Zmieniono hasło</div>
										<div class="label">Dane użytkownika:</div>
										<div class="row">
											<div class="title">Adres email:</div><input type="text" name="email" class="userInput" value="' . $email . '"/>
										</div>
										<div class="row">
											<div class="title">Imię:</div><input type="text" name="name" class="userInput" value="' . $name . '"/>
										</div>
										<div class="row">
											<div class="title">Nazwisko:</div><input type="text" name="lastName" class="userInput" value="' . $lastName . '"/>
										</div>
										<div class="row">
											<div class="title">Kod pocztowy:</div><input type="text" name="pcode" class="userInput" value="' . $pcode . '"/>
										</div>
										<div class="row">
											<div class="title">Ulica:</div><input type="text" name="street" class="userInput" value="' . $street . '"/>
										</div>
										<div class="row">
											<div class="title">Miasto:</div><input type="text" name="city" class="userInput" value="' . $city . '"/>
										</div>
										<div class="row">
											<input type="button" value="Uaktualnij" id="update" name="update" />
										</div>
										<input type="hidden" name="userId" id="userId" value="' . $userId . '" />
										</form>
										<div class="row">
											<div class="title">Wpisz nowe hasło:</div><input type="password" class="userInput" id="newPw" />
										</div>
										<div class="row">
											<input type="button" value="Zmień hasło" id="updatePw" />
										</div>
										<a href="users.php"><input type="button" class="backButton" value="Wróć" /></a>');
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
