<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aktywacja konta</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    <link rel="stylesheet" href="css/borders/regedborders.css" />
</head>
<body>
	<!-- Include background animation ------------------------------------------- -->
	<?php include 'include/backanim.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<div class="bg"></div>
	<div class="bigdiv">
		<div class="rowdiv" id="topdiv">
		</div>
		<div class="rowdiv" id="middiv">
			<div class="rightdiv" id="midrightdiv">
				
			</div>
			<div class="centerdiv" id="midcenterdiv">
				<p>Twoje konto zostało założone. Należy je aktywować za pomocą linka wysłanego na podany przy rejestracji adres email.</p>
				<p id="ret"><a href='shop.php'>powrót do sklepu</a></p>
			</div>
			<div class="leftdiv" id="midleftdiv">
				
			</div>
		</div>
		<div class="rowdiv" id="botdiv">
		</div>
	</div>
</body>
<script type="text/javascript">
$(document).ready(function(){
	setTimeout(function() {
  		window.location.href = "shop.php";
	}, 5000);
});

</script>