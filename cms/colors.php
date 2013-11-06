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
		
		function editColor(){
			$("input.cChange").each(function(){
				$(this).click(function(){
					var id = $(this).attr("id").substr(7,20);	
					var name = $('input#cVal_' + id).val();
					$.ajax({ 
						type: 'POST', 
						url: 'controllers/colorController.php', 
						data: {action : "changeColor", name : name, id : id},
										  
						error: function (data) {
						// alert("porażka!");
						},
						success: function (data) {
							// $("input#" + thisButtonId).attr("value", "Zmień");
							// alert(data);
							$("#confirmAlert").fadeIn("fast");
							$("#confirmAlert").delay(600).fadeOut(800);
							// $("#confirmAlert").css("visibility");
							// window.location.replace("sizes.php");
						},
					});		
			});
			})
		}
		
		
		function deleteColor(){
			$("input.cDel").each(function(){
				$(this).click(function(){
					var id = $(this).attr("id");
					id = id.substr(7,20);
					$.ajax({ 
						type: 'POST', 
						url: 'controllers/colorController.php', 
						data: {action : "deleteColor", id : id},
						 
						error: function (data) {
							// alert("porażka!");
						},
						success: function (data) {
							window.location.replace("colors.php");
						},
					});
				})
			})
		}
		
		function addNewColor(){
			$('input[name="addNew"]').click(function(){
				var name = $('input[name="name"]').val();
				$.ajax({ 
						type: 'POST', 
						url: 'controllers/colorController.php', 
						data: {action : "addColor", name : name},
						 
						error: function (data) {
							// alert("porażka!");
						},
						success: function (data) {
							window.location.replace("colors.php");
						},
					});
			});
		}
		
		$( document ).ready(function() {
			$("#confirmAlert").hide();
			editColor();
			addNewColor();
			deleteColor();
			
			
		});
	</script>
		
		
	</head>
	
	<body>
		
		<div id="siteContainer">
			
			<a href="../index.php"><div>GO TO SHOP</div></a>
			
			<div id="header"><div id="title">ASANTI CMS</div><div id="subtitle">Edytuj rozmiary</div></div>
			
			
			<div id="container">
				
				<div id="leftMenu">
					<!-- Include links ---------------------------------------------------------- -->
					<?php include 'include/leftMenu.php'; ?>
					<!-- ------------------------------------------------------------------------ -->
				</div>
				
				<div id="rightContent">
					
					<div id="container">
						
						<div id="editColors">
							<form action="colors.php"; method="POST" enctype="multipart/form-data">
								<div id="addNew">
									<div class="label">Dodaj nowy</div>
									<div id="name">
										<div class="title">Nazwa:</div>
										<input type="text" name="name"/>
									</div>
									<input type="button" name="addNew" value="Dodaj nowy" />
								</div>
							</form>	
							<div id="confirmAlert">Zmieniono kolor</div>
							<form action=""; method="POST" enctype="multipart/form-data">
								
							
			        			<div id="colors">
			        				
			        				<div class="label">Kolory</div>
			        				
			        				<?php

		
										$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");										
												
										// ENCODING TO UTF8
										$sql = "SET NAMES 'utf8'";
										!mysqli_query($conn,$sql);		
																				
										if (mysqli_connect_errno())
											{
												echo "Failed to connect to MySQL: " . mysqli_connect_error();
											}
																				
																				
																				
										$result1 = mysqli_query($conn,"SELECT * FROM colors ORDER BY colorOf");
																					
										while($row1 = mysqli_fetch_array($result1))
											{
												echo('<div class="cRow">
														<input type="text" id="cVal_' . $row1['id'] . '" class="cVal" value="' . $row1['name'] . '"/>
														<input type="button" class="cChange" id="change_' . $row1['id'] . '" value="Zmień" /><input type="button" class="cDel" id="delete_' . $row1['id'] . '" value="Usuń" />
													</div>');
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

