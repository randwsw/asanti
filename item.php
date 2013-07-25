<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>

    
    <link rel="stylesheet" href="css/shopstyle.css" />
</head>

<body>

    <div class="container">
    	
	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	
	<div id="itemContainer">
		<div id="itemPhotoContainer">
			<img id="itemBigPhoto" src="img/produkty/6.jpg"/>
		</div>
		<div id="itemDescriptionContainer">
			<p id="itemTitle">SUKIENKA Z BANI.</p>
			<p id="itemDescription">Wygodne zapięcie na zatrzask ozdobione perełką.

 
Idealne na chrzest, święta lub wesela.

Wspaniale komponuje się z sukienkami z naszej kolekcji!
 
 
Bolerko dostępne w kolorze:
białym lub ecru
Po zakupie prosimy o informację z kolorem bolerka.

 
 
Ubranko na wzrost 74 cm
WYMIARY BOLERKA:
dł. rękawa od ramionka - 21 cm
szer. pod pachami - 27 cm x2</p>
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
	    
	    $(".sub-menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
			
		// var $tlt = jQuery.noConflict();
		// $tlt(function() {
		// $tlt('.lavamenu').lavalamp();
		// });
	});
	
	
});

$('.imageContainer').mouseenter(function() {
   $(this).children('.imageOverlay').fadeIn(100, function() {
   		$(this).children('.imageOverlay').stop();
   });
});

$('.imageContainer').mouseleave(function() {
	$(this).children('.imageOverlay').fadeOut(100, function() {
		$(this).stop();
});
});


</script>

