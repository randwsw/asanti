<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
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
		
		<div class="product-row" id="middle-row">
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
		</div>
		
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