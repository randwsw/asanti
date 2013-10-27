<?php 
	if(!session_id()){
		session_start();
	} 
?>
<!-- Check login ------------------------------------------------------------ -->
<?php include 'include/checkLog.php'; ?>	
<!-- ------------------------------------------------------------------------ -->

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
	
	
		function changePassword(){
			$("input[name='changePw']").click(function(){
				if($("input[name='newPassword']").val() != $("input[name='repPassword']").val()){
					alert("Wpisane hasła nie zgadzają się!");
				}
				else{
					var newPw = $("input[name='newPassword']").val();
					$.ajax({ 
					    type: 'POST', 
					    url: 'controllers/settingsController.php', 
					    data: {action: "changePass", newPw : newPw},
					    beforeSend: function(){
					    	// $("#progress").show();
					    },
					    complete: function(){
					    	// $("#progress").hide();
					    },
					    error: function (data) {
					    	alert("error");
					    },
					    success: function (data) {
					    	window.location.replace("controllers/logOut.php");
						},
					});
				}
				
			});
		}
		
		function changeLogin(){
			$("input[name='changeLogin']").click(function(){
				if($("input[name='newLogin']").val() != $("input[name='repLogin']").val()){
					alert("Wpisane loginy nie zgadzają się!");
				}
				else{
					var newLog = $("input[name='newLogin']").val();
					$.ajax({ 
					    type: 'POST', 
					    url: 'controllers/settingsController.php', 
					    data: {action: "changeLog", newLog : newLog},
					    beforeSend: function(){
					    	// $("#progress").show();
					    },
					    complete: function(){
					    	// $("#progress").hide();
					    },
					    error: function (data) {
					    	alert("error");
					    },
					    success: function (data) {
					    	window.location.replace("controllers/logOut.php");
						},
					});
				}
				
			});
		}
		
		
		$(document).ready(function(){
			$("#confirmAlert").hide();
			changePassword();
			changeLogin();
		});
		
		</script>
  
</head>




<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header">
			<div id="title">ASANTI CMS</div>
			<div id="subTitle">
				Ustawienia
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
					<div id="settings">
						<div id="confirmAlert">CONF</div>
						<div class="label">Zmień hasło</div>
						<div id="newPw">
							<div>Wpisz nowe hasło</div>
							<input type="text" name="newPassword" />
							<div>Powtórz hasło</div>
							<input type="text" name="repPassword" />
							<input type="button" value="Zmień" name="changePw" />
						</div>
						<div class="label">Zmień login</div>
						<div id="newLog">
							<div>Wpisz nowe login</div>
							<input type="text" name="newLogin" />
							<div>Powtórz login</div>
							<input type="text" name="repLogin" />
							<input type="button" name="changeLogin" value="Zmień" />
						</div>
					</div>
				</div>	
							
			</div>
			
		</div>	
		
		
		<div id="footer">ASANTI CMS FOOTER</div>
		
	</div>
	
</body>
	
	
	
	
	
	
	
	
	
		
</html>
