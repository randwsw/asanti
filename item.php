<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    <!-- Include background animation ------------------------------------------- -->
	<?php include 'include/backanim.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    <link rel="stylesheet" href="css/itemborders.css" />
    
    
    
    
<?php

if(!session_id())
	{
	    session_start();
	} 


// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$itemId = $_GET['id'];
$photosList = array();
$sizeList = array();
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");



// class item {
	// public $id;
	// public $name;
	// public $description;
	// public $headPhotoUrl;
// }
// 
// 
// $itemList = array();
// //////////////////////////////////////////////////////////////////////////////////////////////////// //







// Get item /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}


// CHECK FOR UNIQUE ITEM ////////////////////////////
$result1 = mysqli_query($conn,"SELECT COUNT(*) AS count FROM item WHERE id = '$itemId'");
while($row1 = mysqli_fetch_array($result1))
{
	if($row1['count'] != 1){
		header('Location: shop.php');
	}else{
		// GET ITEM ROWS ///////////////////////////////////
		$result2 = mysqli_query($conn,"SELECT * FROM item WHERE id = '$itemId'");
	
		while($row2 = mysqli_fetch_array($result2))
			{
				$name = $row2['name'];
				$description = $row2['description'];
				$headPhotoId = $row2['headPhotoId'];
			}
			// GET HEAD PHOTO ////////////////////////////////////
			$result3 = mysqli_query($conn,"SELECT * FROM photo WHERE id = '$headPhotoId'");
			
			while($row3 = mysqli_fetch_array($result3))
			{
				$headPhotoUrl = $row3['url'];
			}
			
			$result4 = mysqli_query($conn,"SELECT * FROM photo WHERE item_id = '$itemId' AND isHeadPhoto = '0' ORDER BY orderN ASC");
			
			while($row4 = mysqli_fetch_array($result4))
			{
				$photo = $row4['url'];
				array_push($photosList, $photo);
			}
			
			// SIZES
			
			
			
			// $result5 = mysqli_query($conn,"SELECT s.value AS value FROM size s, size_item si WHERE si.itemId = '$itemId' AND s.id = si.sizeId");
// 			
			// while($row5 = mysqli_fetch_array($result5))
			// {
				// $size = $row5['value'];
				// array_push($sizeList, $size);
			// }
			
			mysqli_close($conn);
	}
}


?>
    
<script type="text/javascript">
    
    $(document).ready(function(){
    	$(".photoThumb").on("click", function(){
    		var url = $(this).attr("src");
   			$(".photoThumb").css("opacity", "1.0");
    		$("#itemBigPhotoImage").stop()
	        .fadeOut(400, function() {
	            $("#itemBigPhotoImage").attr('src', url);
	        })
	        .fadeIn(400);
    		$(this).css("opacity", "0.4");
    		
    		
    	})
    })
    
    </script>
    
    
    
</head>

<body>
	<div class="bg">
		</div>
	    <div class="container">
	    	
		<!-- Include header --------------------------------------------------------- -->
		<?php include 'include/header.php'; ?>
		<!-- ------------------------------------------------------------------------ -->
		<!-- Include submenu -------------------------------------------------------- -->
		<?php include 'include/submenu.php'; ?>
		<!-- ------------------------------------------------------------------------ -->
		
		
		<div id="itemContainer">
			<div id="itemPhotoContainer">
				<div id="itemBigPhoto">
					<span class='helper'></span>
					<img src='<?php echo($photosList[0]); ?>' id='itemBigPhotoImage' />
				</div>		
				<div class="photoThumbnails">
						<ul class="thumbs noscript">
								
							<?php
								foreach($photosList as $photo)
								{
									echo("<li>
													
													<div class='frame'><span class='helper'></span>
														<img src='$photo' class='photoThumb'/>
												</div>
													
										</li>");
								}
							?>
							
						</ul>
					</div>
					<!-- End Gallery Html Containers -->
					<div style="clear: both;"></div>
			</div>			
			<div id="itemDescriptionContainer">
				<div class="bigdiv">
				<div class="rowdiv" id="topdiv">
				</div>
				<div class="rowdiv" id="middiv">
					<div class="rightdiv" id="midrightdiv">
							
					</div>
					<div class="centerdiv" id="midcenterdiv">
				<div id="container">
					<h2 id="itemTitle"><?php echo("$name"); ?><h2>
					<div id="itemDescription"><?php echo("$description"); ?></div>
					
					
					<div id="itemSizes"><div id="title">Dostępne rozmiary:</div>
						<?php
						$conn2=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
						
						if (mysqli_connect_errno())
						{
					 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
						
						// ENCODING TO UTF8
						$sql = "SET NAMES 'utf8'";
						!mysqli_query($conn2,$sql);	
						
						
						
						$sizeNames = array();
					
						$result5 = mysqli_query($conn2,"SELECT s.name AS name FROM size s, size_item si WHERE si.itemId = '$itemId' AND s.id = si.sizeId GROUP BY s.name ORDER BY s.name DESC");
						
						while($row5= mysqli_fetch_array($result5))
						{
							$name = $row5['name'];
							array_push($sizeNames, $name);
						}
						
						foreach($sizeNames as $s){
							echo("<div class='sizeBox'><div class='title'>" . ucfirst($s) . ":</div><div class='styled-select'><label><select>");
							
							$result6 = mysqli_query($conn2, "SELECT s.value AS value FROM size s, size_item si WHERE si.itemId = '$itemId' AND s.id = si.sizeId AND s.name = '$s'");
							
							while($row6= mysqli_fetch_array($result6))
							{
								$value = $row6['value'];
								echo("<option value='$value'>$value cm</option>");
								
							}
							echo("<label></select></div></div>");
						}
					 	?>
					 </div>
					 <div id="cart">
					 	<a class="item-cart" <?php echo("id='item_".$_GET["id"]."' ") ?>>
					 		<img class="cart-image" src="img/cart-big-dark.png" />
					 	</a>
					 </div>
					 <div id="questions">
					 	<a href="shop.php">
					 		<div id="mark">? </div> Zadaj pytanie odnośnie przedmiotu.
					 	</a>
					 </div>
					</div>
					</div>
			<div class="leftdiv" id="midleftdiv">
				
			</div>
		</div>
		<div class="rowdiv" id="botdiv">
		</div>
	</div> 
			</div>
			</div>
		 </div>
</body>      
</html>
<script type="text/javascript">
	
$( document ).ready(function() {
checkCart();
var height = $( '#midcenterdiv' ).css( "height" );
$( '#midrightdiv, #midleftdiv, #middiv' ).css( "height", height );

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

function checkCart(){
		var count=0;
		if (jQuery.cookie("cartItem")) {
		var cookieval = $.cookie("cartItem");
		cookieval+=",";
		for (var i=0; i < cookieval.length; i++) {
			if(cookieval.charAt(i)!=',')
			{				
			}
			else
			{
				count++;
				item="";
			}
		}
	}
	else{
		count=0;		
	}
	$('#cart-count').html(count);
}

$('.item-cart').click(function() {
	
	var cookieArray = [];
	if (jQuery.cookie("cartItem")) {
		var cookieval = $.cookie("cartItem");
		cookieval+=",";
		var item="";		
		for (var i=0; i < cookieval.length; i++) {
			if(cookieval.charAt(i)!=',')
			{
				item += cookieval.charAt(i);
			}
			else
			{
				cookieArray.push(item);
				item="";
			}
		}
	}
	else{		
	}
	if(jQuery.inArray($(this).attr('id'), cookieArray)!=-1)	{
			var id = jQuery.inArray($(this).attr('id'), cookieArray);
			//alert(id);
			// cookieArray[id]+="#2#"
		}
	else {
		// cookieArray.push($(this).attr('id'));
	}
	
	cookieArray.push($(this).attr('id'));	
	$.cookie("cartItem", cookieArray, { expires: 1, path: '/' });
	alert("Produkt został dodany do koszyka");
	checkCart();
});
</script>

