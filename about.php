<?php if(!session_id()) { session_start();} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    <link rel="stylesheet" href="css/borders/aboutborders.css" />
    
</head>

<body>
	<!-- Include autoremeber --------------------------------------------------------- -->
	<?php include 'controllers/autoRemember.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<!-- Include background animation ------------------------------------------- -->
	<?php include 'include/backanim.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<div class="bg">
	</div>
	<div class="bigdiv">
		<div class="rowdiv" id="topdiv">
		</div>
		<div class="rowdiv" id="middiv">
			<!-- Original texture used: http://subtlepatterns.com/wave-grind/ -->
			<div class="rightdiv" id="midrightdiv">
				
			</div>
			<div class="centerdiv" id="midcenterdiv">
				<div id="aboutContent">
					<div id="title">O nas</div>
	   			<?php
	   			
	   			$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
							
				if (mysqli_connect_errno())
					{
						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
							
				// ENCODING TO UTF8
				$sql = "SET NAMES 'utf8'";
				!mysqli_query($conn,$sql);	
	
				$result = mysqli_query($conn,"SELECT content FROM pages WHERE name = 'about'");
							
				while($row= mysqli_fetch_array($result))
					{
						echo($row['content']);
					}
				mysqli_close($conn);
	   			
	   			?>
	   			</div>
			</div>
			<div class="leftdiv" id="midleftdiv">
				
			</div>
		</div>
		<div class="rowdiv" id="botdiv">
		</div>
	</div>
	<?php include 'include/footer.php'; ?> 
</body>
</html>
<script>
	$(document).ready(function(){
		var height = $( '#midcenterdiv' ).css( "height" );
	 	$( '#midrightdiv, #midleftdiv, #middiv' ).css( "height", height );
	});
</script>