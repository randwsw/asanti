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
				<div id="paymentsContent">
					<div id="title">Płatności i dostawa</div>
	   			<?php
	   			
	   			$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
				mysqli_set_charset($conn, "utf8");
				
							
				if (mysqli_connect_errno())
					{
						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
							
				
	
				$result = mysqli_query($conn,"SELECT content FROM pages WHERE name = 'payments'");
							
				while($row= mysqli_fetch_array($result))
					{
						echo($row['content']);
					}
				mysqli_close($conn);
	   			
	   			?>
	   			<div id="shipping">
						<?php
							$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
							mysqli_set_charset($conn, "utf8");							
								
							if (mysqli_connect_errno())
								{
									echo "Failed to connect to MySQL: " . mysqli_connect_error();
								}
								
							$result = mysqli_query($conn,"SELECT * FROM shipping WHERE active = 1");
							
							if(mysqli_num_rows($result)>0) {
								echo("<div id='t1'>Dostępne sposoby wysyłki:</div>");	
								while($rec = mysqli_fetch_array($result)) {
									$shippname =  $rec['name'];
									$shippval =  $rec['value'];
									echo("
									<p class='pdiscount'>&#8226 ".$shippname." - <a>".$shippval." zł</a><p>
									");
								}
							}
							mysqli_close($conn); 
						?>
					</div>
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