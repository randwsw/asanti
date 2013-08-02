<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script src="js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    <script type="text/javascript" src="js/jquery.watermark.min.js"></script>
    
    <link rel="stylesheet" href="css/shopstyle.css" />
    <link rel="stylesheet" href="css/sliderstyle.css" />
    
</head>
<body>
	<div class ="container">
	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
		<div class="product-row" id="top-row">
			<div class="column-name">
				<p>Nazwa produktu</p>
			</div>
			<div class="column-price">
				<p>Cena za sztukę</p>
			</div>
			<div class="column-quantity">
				<p>Ilość</p>
			</div>
			<div class="column-price-all">
				<p>Cena całkowita</p>
			</div>
			<div class="column-remove">
				<p>Usuń</p>
			</div>
		</div>
		
		<!-- <div class="product-row" id="middle-row">
			<div class="column-name">
				<p>Produkt1</p>
			</div>
			<div class="column-price">
				<p>24,99</p>
			</div>
			<div class="column-quantity">
				<input type="text"/>
			</div>
			<div class="column-price-all">
				<p>24,99</p>
			</div>
			<div class="column-remove">
				<a href=""><p>X</p></a>
			</div>
		</div>
		
		<div class="product-row" id="middle-row">
			<div class="column-name">
				<p>Produkt2</p>
			</div>
			<div class="column-price">
				<p>34,99</p>
			</div>
			<div class="column-quantity">
				<input type="text"/>
			</div>
			<div class="column-price-all">
				<p>34,99</p>
			</div>
			<div class="column-remove">
				<a href=""><p>X</p></a>
			</div>
		</div> -->
		<?php 
		if(isset($_COOKIE['cartItem']))
        {
        		$querypart="";
                $var = $_COOKIE['cartItem'];
				$var = $var.',';
				$item="";
				$arr = array();
				$test = array();
				
                for ($i = 0; $i<strlen($var); $i++)  {
				    $character = substr($var, $i,1);
					if($character != ',')
					{
						$item= $item.$character;						
					}
					else {						
						$item = substr($item, 5);
						if (in_array($item, $arr, true)) {
							$test[$item]+=1;				 
						}
						else{
							$test[$item]=1;
							$querypart= $querypart."i.id = ".$item." OR ";  			 	
						}
						array_push($arr,$item);

						$item="";
					}
				}
				
			$querypart = substr($querypart,0,-3);
			// echo($querypart);
			// print_r($arr);
			// print_r($test);
			
			// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
			$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
			// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
					
			// Check connection
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
		  	 //echo("SELECT i.name AS iname, value, i.id FROM item i, price pr WHERE ".$querypart.";");
			
			$sql= mysqli_query($conn, "SELECT i.name AS iname, price, i.id FROM item i WHERE ".$querypart.";");
			
			while($rec = mysqli_fetch_array($sql)) {
				echo(
				"<div class='product-row' id='middle-row'>
					<div class='column-name'>
						<p>".$rec['iname']."</p>
					</div>
					<div class='column-price'>
						<p>".$rec['price']."</p>
					</div>
					<div class='column-quantity'>
						<input type='text' value='".$test[$rec['id']]."' id='quantity".$rec['id']."'/>
					</div>
					<div class='column-price-all'>
						<p>".$rec['price']*$test[$rec['id']].".00"."</p>
					</div>
					<div class='column-remove'>
						<a href=''><p>X</p></a>
					</div>
				</div>"
				);
			}
		  
        }
		?>
	</div>
</body>
</html>
<script type="text/javascript">
$( document ).ready(function() {


	$(function() {			
	    $(".menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
			
	});
	
});
</script>