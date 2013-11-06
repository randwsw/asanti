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
	automatyczne generowanie optionów w select dla rozmiarów
 -->


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
	
	
		function updateShipping(){
			$("input[name='submit']").click(function(){
				var shipId = $(this).attr("id").substr(5,5);
				var name = $("input#count_" + shipId).val();
				var value = $("input#disc_" + shipId).val();
				$.ajax({ 
				    type: 'POST', 
				    url: 'controllers/shippingController.php', 
				    data: {action: "updateShip", shipId: shipId, name: name, value: value},
				    beforeSend: function(){
				    	// $("#progress").show();
				    },
				    complete: function(){
				    	// $("#progress").hide();
				    },
				    error: function (data) {
				    	// alert("error");
				    },
				    success: function (data) {
				    	$("#confirmAlert").fadeIn("fast");
						$("#confirmAlert").delay(800).fadeOut(800);
					},
				});
			});
		}
		
		function changeActiveShipping(){
		var active;
		$(".squaredOneCheckbox").change(function(){
			if($(this).is(':checked')){
				active = 1;
			}else{
				active = 0;
			}
			var shipId = $(this).attr("id");
			shipId = shipId.substr(10,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/shippingController.php', 
			    data: {action: "changeActive", shipId: shipId, active: active},
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
		
		
		function deleteShip(){
			$("input[name='delete']").each(function(){
				$(this).click(function(){
					var shipId = $(this).attr("id");
					shipId = shipId.substr(5,10);
					$.ajax({ 
						type: 'POST', 
						url: 'controllers/shippingController.php', 
						data: {action : "deleteShip", shipId : shipId},
						 
						error: function (data) {
							// alert("porażka!");
						},
						success: function (data) {
							window.location.replace("shipping.php");
						},
					});
				})
			})
		}
		
		
		
		
		$(document).ready(function(){
			$("#confirmAlert").hide();
			updateShipping();
			changeActiveShipping();
			deleteShip();
		});
		
		</script>
  
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header">
			<div id="title">ASANTI CMS</div>
			<div id="subTitle">
				Dostawa
			</div>
		</div>
		
		<div id="container">
			
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			
			<div id="rightContent">
				
				<div id="container">
					<div id="shipping">
						<div id="newShipping">
							<form action="controllers/shippingController.php"; method="POST" enctype="multipart/form-data">
								<div class="label">Dodaj nowy</div>
								<div class="row"><div>Nazwa: </div><input type="text" name="newName"/></div>
								<div class="row"><div>Kwota: </div><input type="text" name="newValue"/> zł</div>
								<input type="hidden" name="action" value="addNew" />
								<input type="submit" name="submit" value="Dodaj" />
							</form>
						</div>
						<div class="label">Opcje dostawy</div>
						<div id="confirmAlert">Pomyślnie zmieniono</div>
						<table id="shipping">
							<tr class="head">
								<td id="count">Nazwa</td>
								<td id="disc">Koszt</td>
								<td id="options">Aktywny</td>
							</tr>
							
							<?php
							
								$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
								
								$sql = "SET NAMES 'utf8'";
									!mysqli_query($conn,$sql);
									
									
									if (mysqli_connect_errno())
											  {
											  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
											  }
								$result = mysqli_query($conn,"SELECT * FROM shipping");

										
										while($row = mysqli_fetch_array($result))
											{
												$id = $row['id'];
												$name = $row['name'];
												$value = $row['value'];
												$active = $row['active'];
												if($active == 1){
													$checked = "checked";
												}
												else{
													$checked = "";
												}
												echo("<tr class='cont'>
														<td>
															<input type='text' id='count_" . $id . "' name='name' value='" . $name . "'/>
														</td>
														<td>
															<input type='text' id='disc_" . $id . "' name='value' value='" . $value . "'/> zł
														</td>
														<td>
															<div class='squaredOne'>
																<input type='checkbox' value='None' " . $checked . " class='squaredOneCheckbox' id='squaredOne" . $id . "' name='check' />
																<label for='squaredOne" . $id . "'></label>
															</div>
															<input type='button' id='disc_" . $id . "' name='submit' value='Zapisz zmiany' />
															<input type='button' id='disc_" . $id . "' name='delete' value='Usuń' />
														</td>
													</tr>");
											}
									mysqli_close($conn);
							?>
						</table>
						
					</div>
				</div>	
							
			</div>
			
		</div>	
		
		
		<div id="footer">ASANTI CMS FOOTER</div>
		
	</div>
	
</body>
	
	
	
	
	
	
	
	
	
		
</html>
