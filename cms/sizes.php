<?php 
	if(!session_id()){
		session_start();
	} 
?>
<!-- Check login ------------------------------------------------------------ -->
<?php include 'include/checkLog.php'; ?>	
<!-- ------------------------------------------------------------------------ -->

<!-- 
	
	Dodaj walidatory w jquery, żeby nie można było dodawać pustych rekordów
	Coń nie bangla przy drugiej zmianie nazwy
	
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
		
		function editSizeOfPool(){
			$("input.edit").each(function(){
				$(this).click(function(){
					if($(this).attr("id").substr(0,11) == "editSizeOf_"){
						var sizeOf = $(this).attr("id").substr(11,30);
						var val = $("input#size_" + sizeOf).val();
						$.ajax({ 
							type: 'POST', 
							url: 'controllers/sizeController.php', 
							data: {action : "changeSize", sizeOf : sizeOf, newSizeName: val, option : "sizeOf"},
										  
							error: function (data) {
							alert("porażka!");
							},
							success: function (data) {
								// $("input#" + thisButtonId).attr("value", "Zmień");
								// alert(data);
								// $("#confirmAlert").fadeIn("fast");
								// $("#confirmAlert").delay(600).fadeOut(800);
								// $("#confirmAlert").css("visibility");
								window.location.replace("sizes.php");
							},
						});
					}else{
						if($(this).attr("id").substr(0,5) == "edit_"){
							var id = $(this).attr("id").substr(5,10);
							var val = $("input#size_" + id).val();
							$.ajax({ 
								type: 'POST', 
								url: 'controllers/changeSizeController.php', 
								data: {id : id, value: val, option : "id"},
											  
								error: function (data) {
								alert("porażka!");
								},
								success: function (data) {
									// $("input#" + thisButtonId).attr("value", "Zmień");
									// alert(data);
									// $("#confirmAlert").fadeIn("fast");
									// $("#confirmAlert").delay(600).fadeOut(800);
									// $("#confirmAlert").css("visibility");
									window.location.replace("sizes.php");
								},
							});
						}
					}
			});
			})
		}
		
		
			
		function addSize(){
			$("input.add").each(function(){
				$(this).click(function(){
					
					var sizeOf = $(this).attr("id").substr(4,30);
					var name = $("input#size_" + sizeOf).val();
					var val = $("input#newSize_" + sizeOf).val();
					$.ajax({ 
						type: 'POST', 
						url: 'controllers/sizeController.php', 
						data: {action : "addValue", name : name, value : val, sizeOf : sizeOf},
						 
						error: function (data) {
							// alert("porażka!");
						},
						success: function (data) {
							// alert("success");
						window.location.replace("sizes.php");
						},
					});
				});
			});
		}
		
		
		function deleteSize(){
			$("input.delete").each(function(){
				$(this).click(function(){
					var id = $(this).attr("id");
					id = id.substr(7,10);
					$.ajax({ 
						type: 'POST', 
						url: 'controllers/sizeController.php', 
						data: {action : "delete", sizeId : id},
						 
						error: function (data) {
							alert("porażka!");
						},
						success: function (data) {
							window.location.replace("sizes.php");
							
						},
					});
				})
			})
		}
		
		
		
		$( document ).ready(function() {
			editSizeOfPool();
			addSize();
			deleteSize();
			$("#confirmAlert").hide();
		});
	</script>
		
		
	</head>
	
	<body>
		
		<div id="siteContainer">
			
			<a href="../shop.php"><div>GO TO SHOP</div></a>
			
			<div id="header"><div id="title">ASANTI CMS</div><div id="subtitle">Edytuj rozmiary</div></div>
			
			
			<div id="container">
				
				<div id="leftMenu">
					<!-- Include links ---------------------------------------------------------- -->
					<?php include 'include/leftMenu.php'; ?>
					<!-- ------------------------------------------------------------------------ -->
				</div>
				
				<div id="rightContent">
					
					<div id="container">
						
						<div id="editSize">
							<form action="sizes.php"; method="POST" enctype="multipart/form-data">
								<div id="addNew">
									<div class="label">Dodaj nowy</div>
									<div id="name">
										<div class="title">Nazwa:</div>
										<input type="text" name="name"/>
									</div>
									<div id="value">
										<div class="title">Wartość:</div>
										<input type="text" name="value"/>
									</div>
									<input type="submit" name="submit" value="Dodaj nowy" />
								</div>
							</form>	
							<div id="confirmAlert">Zmieniono nazwę.</div>
							<form action=""; method="POST" enctype="multipart/form-data">
								
							
			        			<div id="size">
			        				
			        				<div class="label">Rozmiary</div>
			        				
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
																<div class='container'><input type='text' class='editTextbox' id='size_$sizeOf->sizeOf' value='$sizeOf->name' />
																</div>
																<div class='buttons'>
																		<input type='button' class='edit' id='editSizeOf_$sizeOf->sizeOf' value='Zmień' />
																		" . //<input type='button' class='delete' id='deleteSizeOf_$sizeOf->sizeOf' value='Usuń' /> 
																"</div>
														</div>");
											foreach($sizeList as $s){
												echo("<div class='size' id='size_$s->id'><input type='text' class='editTextbox' id='size_$s->id' value='$s->value' />
															<div class='buttons'>
																		<input type='button' class='edit' id='edit_$s->id' value='Zmień' />
																		<input type='button' class='delete' id='delete_$s->id' value='Usuń' />
															</div>
												</div>");
											}
											echo("<input type='text' class='newSizeTextbox' id='newSize_$sizeOf->sizeOf' value='' />
													<div class='buttons'>
															<input type='button' class='add' id='add_$sizeOf->sizeOf' value='Dodaj rozmiar' />
													</div>");
											echo("</div>");
											unset($sizeList);
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
$name = $_POST['name'];
$value = $_POST['value'];

$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
			$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
			$sizeOf =  str_replace($aWhat, $aOn, $name);

// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	$sql="INSERT INTO size (value, name, sizeOf)
		VALUES
		('$value','$name','$sizeOf')";
		
		if (!mysqli_query($conn,$sql))
		  {
			  die('Error: ' . mysqli_error($conn));
			  mysqli_close($conn);
		  }else
		  {
		  	mysqli_close($conn);
		  }

header("Location: sizes.php");

}



?>