<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>

    
    <link rel="stylesheet" href="css/shopstyle.css" />
    <link rel="stylesheet" href="css/sliderstyle.css" />
    
</head>

<body>

    <div class="container">
   	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<!-- Include background animation ------------------------------------------- -->
	<!--<?php include 'include/backanim.php'; ?> -->
	<!-- ------------------------------------------------------------------------ -->
	<?php
		$cat = 'all';
		if(isset($_GET['category'])) {
		    $cat = $_GET['category'];
		}
	?>
        
    	<div class="sub-menu-container"> 
		<div class="sub-menu">
		                
			<ul class="sub-menu-anim">
				
					<?php if ($cat == 'all') : ?>               
		        	<li class="current">
		        	<?php else : ?>
		        	<li class="other">
		        	<?php endif; ?>               
					<a href="shop.php"><div class="menu-div">Wszystko</div></a></li>
					
					<?php if ($cat == 'shoes') : ?>               
		        	<li class="current">
		        	<?php else : ?>
		        	<li class="other">
		        	<?php endif; ?>                                        
		            <a href="shop.php?category=shoes"><div class="menu-div">Obuwie</div></a></li>
		            
		            <?php if ($cat == 'shoes') : ?>               
		        	<li class="current">
		        	<?php else : ?>
		        	<li class="other">
		        	<?php endif; ?>  
		            <a href="shop.php"><div class="menu-div">Nakrycia głowy</div></a></li>
		            
		            <?php if ($cat == 'shoes') : ?>               
		        	<li class="current">
		        	<?php else : ?>
		        	<li class="other">
		        	<?php endif; ?>  
		        	<a href="shop.php"><div class="menu-div">Kurtki i płaszcze</div></a></li>
		        	
		        	<?php if ($cat == 'set') : ?>               
		        	<li class="current">
		        	<?php else : ?>
		        	<li class="other">
		        	<?php endif; ?>     
		    		<a href="shop.php?category=set"><div class="menu-div">Komplety</div></a></li>                    
			</ul>                
		</div>
			<div class="cart-div">
				<p class="cart-desc">Koszyk</p>
				<img class="cart-image" src="img/cart-big-dark.png" alt="Smiley face">
				<p class="item-count">0</p>
			</div>
		</div>

		
	<div class="products">
		<?php
			
		// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
				
		// Check connection
		if (mysqli_connect_errno())
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		if($cat!='all')
		{
			$sql= mysqli_query($conn, "SELECT i.name AS iname, value, url, i.id FROM item i, price pr, photo ph, category c, category_con cc WHERE i.headPhotoId = ph.id AND c.name ='$cat' AND cc.item_id = i.id AND cc.cat_id =c.id;") or die(mysql_error());
		}
		else {
			$sql= mysqli_query($conn, "SELECT name AS iname, value, url, i.id FROM item i, price pr, photo ph WHERE i.headPhotoId = ph.id;") or die(mysql_error());			
		}
		
		while($rec = mysqli_fetch_array($sql)) {
			echo("<div class='product-info'>
	 		<div class='product-price'>
	 			<p>".$rec['value']."</p>
	 		</div>
	     	<div class='imageContainer'>
				<div class='imageOverlay' id='item_".$rec['id']."' >
					<a href='item.php?id=".$rec['id']."' >
					<div class='eye'></div>
					</a>
					<div class='cart'></div>	 	    	
		    	</div>		    	
		     	<img class='productImage' src='".$rec['url']."' alt='Smiley face' >
		    </div>
		    <div class='product-name'>
	 			<p>".$rec['iname']."</p>
	 		</div> 
	    </div>");
		  	
		} 
		
		
		
		mysqli_close($conn);
		
		?>
	 	<!-- <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
				<div class="imageOverlay" >
					<div class="eye"></div>
					<div class="cart"></div>	 	    	
		    	</div>
		     	<img class="productImage" src="img/produkty/1.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    </a>
	 	<div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
				<div class="imageOverlay">
					<div class="eye"></div>
					<div class="cart"></div>	 	    	
		    	</div>
		     	<img class="productImage" src="img/produkty/2.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/3.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/4.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/5.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/6.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/7.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/8.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    	 	<div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/1.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	 	<div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/2.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/3.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div>
	    
	    <div class="product-info">
	 		<div class="product-price">
	 			<p>9000$</p>
	 		</div>
	     	<div class="imageContainer">
		     	 <img class="productImage" src="img/produkty/4.png" alt="Smiley face" >
		    </div>
		    <div class="product-name">
	 			<p>Nazwa</p>
	 		</div> 
	    </div> -->
     	 
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
