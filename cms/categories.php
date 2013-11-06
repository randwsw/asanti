<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}
?>

<!-- 
	
	Dodaj walidatory w jquery, żeby nie można było dodawać pustych rekordów
	
 -->


<html>
	<head>
		<?php include "../include/links.php"; ?>
		
		<script src="<http://deepliquid.com/Jcrop/js/jquery.Jcrop.min.js>"></script>
		<script src="../js/jcrop/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="../js/jcrop/jquery.Jcrop.css" type="text/css" />
		<link rel="stylesheet" href="../css/demos.css" type="text/css" />
		<link rel="stylesheet" href="../css/jquery.Jcrop.css" type="text/css" />
		<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
		<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
		
		
		
		
		
		
		
		<script type="text/javascript">
		
		function editPool(){
			$("input.edit").each(function(){
				$(this).click(function(){
				var thisButtonId = $(this).attr("id");
				var id = $(this).attr("id");
				id = id.substr(5,10);
				
					var val = $("input#cat_" + id).val();
					// alert(val);
							$.ajax({ 
							    type: 'POST', 
							    url: 'controllers/categoryController.php', 
							    data: {action : "change", categoryId: id, categoryName: val},
							  
							    error: function (data) {
							    	// alert("porażka!");
							    },
							    success: function (data) {
							    	// $("input#" + thisButtonId).attr("value", "Zmień");
							    	// alert("sukces");
							    	$("#confirmAlert").fadeIn("fast");
							    	$("#confirmAlert").delay(800).fadeOut(800);
							    	// $("#confirmAlert").css("visibility")
								},
							});
					
			});
			})
		}
		
		
					
					
		function addCategory(){
			$("input.add").each(function(){
				$(this).click(function(){
					var id = $(this).attr("id");
					id = id.substr(4,10);
					var val = $("input#newCategory_" + id).val();
					var parentId = id;
					$.ajax({ 
						type: 'POST', 
						url: 'controllers/categoryController.php', 
						data: {action : "add", categoryName : val, parentId : parentId},
						 
						error: function (data) {
							// alert("porażka!");
						},
						success: function (data) {
							// alert("success");
						window.location.replace("categories.php");
						},
					});
				});
			});
		}
		
		
		function deleteCategory(){
			$("input.delete").each(function(){
				$(this).click(function(){
					var id = $(this).attr("id");
					id = id.substr(7,10);
					
					$.ajax({ 
						type: 'POST', 
						url: 'controllers/categoryController.php', 
						data: {action : "delete", categoryId : id},
						 
						error: function (data) {
							// alert("porażka!");
						},
						success: function (data) {
							if(data == "success"){
								window.location.replace("categories.php");
							}else{
								
							}
							
						},
					});
				})
			})
		}
		
		
		
		$( document ).ready(function() {
			editPool();
			addCategory();
			deleteCategory();
			$("#confirmAlert").hide();
		});
	</script>
		
		
	</head>
	
	<body>
		
		<div id="siteContainer">
			
			<a href="../index.php"><div>GO TO SHOP</div></a>
			
			<div id="header"><div id="title">ASANTI CMS</div><div id="subtitle">Edytuj kategorie</div></div>
			
			
			<div id="container">
				
				<div id="leftMenu">
					<!-- Include links ---------------------------------------------------------- -->
					<?php include 'include/leftMenu.php'; ?>
					<!-- ------------------------------------------------------------------------ -->
				</div>
				
				<div id="rightContent">
					
					<div id="container">
						
						<div id="editCategories">
							<div id="confirmAlert">Zmieniono nazwę.</div>
							<form action=""; method="POST" enctype="multipart/form-data">
								
							
			        			<div id="category">
			        				
			        				<div class="label">Wybierz kategorię</div>
			        				
			        				<?php

		
										$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");										
										class category {
											public $id;
											public $name;
										}
																				
										// ENCODING TO UTF8
										$sql = "SET NAMES 'utf8'";
										!mysqli_query($conn,$sql);		
																				
										if (mysqli_connect_errno())
											{
												echo "Failed to connect to MySQL: " . mysqli_connect_error();
											}
																				
																				
																				
										$result = mysqli_query($conn,"SELECT COUNT(*) AS cl1_count FROM category WHERE catLevel = 1");
																					
										while($row1 = mysqli_fetch_array($result))
											{
												$cl1_count = $row1['cl1_count'];
											}
											
											
											
										for($i = 0; $i < $cl1_count; $i++){
											$categoryList = array();
											$result2 = mysqli_query($conn,"SELECT * FROM category WHERE parentId = 0 LIMIT $i, 1");
																					
											while($row2 = mysqli_fetch_array($result2))
												{
													$cl1_id = $row2['id'];
													$cl1_name = $row2['name'];
												}
												
											$result3 = mysqli_query($conn,"SELECT * FROM category WHERE parentId = $cl1_id");
																					
											while($row3 = mysqli_fetch_array($result3))
												{
													$category = new category;
													$category->id = $row3['id'];
													$category->name = $row3['name'];
													array_push($categoryList, $category);
												}
											
											echo("<div class='box'>
														<div class='cl1' id='cl1_$cl1_id'>
																<div class='container'><input type='text' class='editTextbox' id='cat_$cl1_id' value='$cl1_name' />
																</div>
																<div class='buttons'>
																		<input type='button' class='edit' id='edit_$cl1_id' value='Zmień' />

																</div>
														</div>");
											foreach($categoryList as $c){
												echo("<div class='cl2' id='cat_$c->id'><input type='text' class='editTextbox' id='cat_$c->id' value='$c->name' />
															<div class='buttons'>
																		<input type='button' class='edit' id='edit_$c->id' value='Zmień' />
																		<input type='button' class='delete' id='delete_$c->id' value='Usuń' />
															</div>
												</div>");
											}
											echo("<input type='text' class='newCategoryTextbox' id='newCategory_$cl1_id' value='' />
													<div class='buttons'>
															<input type='button' class='add' id='add_$cl1_id' value='Dodaj kategorię' />
													</div>");
											echo("</div>");
											unset($categoryList);
										}
										
										mysqli_close($conn);
										
										
										?>
		
			        			</div>

							</form>
							
							
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


<?php 



if(isset($_POST["submit"])){

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$itemId = $_POST['passItemId'];
$categoryId = $_POST['categoryToPost'];
$itemName = $_POST['name'];
$itemDescription = $_POST['description'];
$itemPrice = $_POST['price'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	echo("item id: " . $itemId . "   nazwa: " . $itemName . "   cena: " . $itemPrice . "  opis: " . $itemDescription);


	mysqli_query($conn,'UPDATE item SET name = "' . $itemName . '", description = "' . $itemDescription . '", price = "' . $itemPrice . '" WHERE id = "' . $itemId . '"');
	mysqli_query($conn,"UPDATE category_con SET cat_id = $categoryId WHERE item_id = $itemId");



mysqli_close($conn);

header("Location: editItem.php?itemId=" . $itemId);

}



?>