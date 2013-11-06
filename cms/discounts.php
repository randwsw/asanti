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
	
	
		function updateDiscount(){
			$("input[name='submit']").click(function(){
				var discId = $(this).attr("id").substr(5,5);
				var count = $("input#count_" + discId).val();
				var disc = $("input#disc_" + discId).val();
				$.ajax({ 
				    type: 'POST', 
				    url: 'controllers/discountController.php', 
				    data: {action: "updateDisc", discId: discId, count: count, disc: disc},
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
		
		function changeActiveDiscount(){
		var active;
		$(".squaredOneCheckbox").change(function(){
			if($(this).is(':checked')){
				active = 1;
			}else{
				active = 0;
			}
			var discId = $(this).attr("id");
			discId = discId.substr(10,5);
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/discountController.php', 
			    data: {action: "changeActive", discId: discId, active: active},
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
		
		
		$(document).ready(function(){
			$("#confirmAlert").hide();
			updateDiscount();
			changeActiveDiscount();
			
		});
		
		</script>
  
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header">
			<div id="title">ASANTI CMS</div>
			<div id="subTitle">
				Rabaty
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
					<div id="discounts">
						<div class="label">Dostępne rabaty</div>
						<div id="confirmAlert">Zmieniono rabat</div>
						<table id="discounts">
							<tr class="head">
								<td id="count">Kwota zamówienia</td>
								<td id="disc">Zniżka w %</td>
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
								$result = mysqli_query($conn,"SELECT * FROM discount");

										
										while($row = mysqli_fetch_array($result))
											{
												$id = $row['id'];
												$itemValue = $row['items_value'];
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
															<input type='text' id='count_" . $id . "' name='count' value='" . $itemValue . "'/>
														</td>
														<td>
															<input type='text' id='disc_" . $id . "' name='disc' value='" . $value . "'/>
														</td>
														<td>
															<div class='squaredOne'>
																<input type='checkbox' value='None' " . $checked . " class='squaredOneCheckbox' id='squaredOne" . $id . "' name='check' />
																<label for='squaredOne" . $id . "'></label>
															</div>
															<input type='button' id='disc_" . $id . "' name='submit' value='Zapisz zmiany' />
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
