<?php if(!session_id()) { session_start();} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    <link rel="stylesheet" href="css/borders/itemborders.css" />
    
    
    
    
<?php


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
				$active = $row2['active'];
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
    	
    	var height = $( '#midcenterdiv' ).css( "height" );
	 	$( '#midrightdiv, #midleftdiv, #middiv' ).css( "height", height );
    })
    
    </script>
    
    
    
</head>

<body>
	<!-- Include background animation ------------------------------------------- -->
	<?php include 'include/backanim.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
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
					<h2 id="itemTitle"><?php echo("$name"); ?></h2>
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
							echo("<div class='sizeBox'><div class='title'>" . ucfirst($s) . ":</div><div class='styled-select'><label><select class='size-sel'>");
							
							$result6 = mysqli_query($conn2, "SELECT s.value AS value FROM size s, size_item si WHERE si.itemId = '$itemId' AND s.id = si.sizeId AND s.name = '$s'");
							
							while($row6= mysqli_fetch_array($result6))
							{
								$value = $row6['value'];
								echo("<option value='$value'>$value cm</option>");
								
							}
							echo("<label></select></div></div>");
						}
						mysqli_close($conn2);
					 	?>
					 </div>
					 <div id="cart">
					 	<?php if($active==1){ ?>
					 	<a class="item-cart" <?php echo("id='item_".$_GET["id"]."' ") ?>>
					 		<img class="cart-image" src="img/cart-big-dark.png" />
					 	</a>
					 	<?php } else { ?>
					 	<a class="no-item-cart">
					 		<img class="cart-image" src="img/cart-big-dark.png" /><p>Produkt niedostępny</p>
					 	</a>
					 	<?php } ?>
					 </div>
					 <div id="questions">
					 	<a href="shop.php">
					 		<div id="mark">? </div> Zadaj pytanie odnośnie przedmiotu.
					 	</a>
					 </div>
					 <?php
					 	$conn3=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
						$id = $_GET['id'];
						
						
						if (mysqli_connect_errno())
						{
					 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
						
						// ENCODING TO UTF8
						$sql = "SET NAMES 'utf8'";
						!mysqli_query($conn3,$sql);	
						
						// $result6 = mysqli_query($conn3,"SELECT i.name AS itemName, p.url AS photoUrl FROM item i, photo p WHERE i.headPhotoId = p.id AND ");
						$result6 = mysqli_query($conn3,"SELECT i.id AS itemId, i.name AS itemName FROM item i, item_conn ic WHERE (ic.item1_id = $id AND i.id = ic.item2_id) OR (ic.item2_id = $id AND i.id = ic.item1_id) GROUP BY itemName");
						
						echo('<div id="connections">
								<div class = "Title">Przedmioty powiązane:</div>');
						while($row6= mysqli_fetch_array($result6))
						{
							$itemName = $row6['itemName'];
							$itemId = $row6['itemId'];
							$result7 = mysqli_query($conn3,"SELECT p.url AS photoUrl FROM photo p, item i WHERE i.id = $itemId AND p.id = i.headPhotoId");
							while($row7= mysqli_fetch_array($result7))
							{
								$photoUrl = $row7['photoUrl'];
								echo('<a href="item.php?id='.$itemId.'"><div class="connectionBox">
										<img src="' . $photoUrl . '" class="connectionImg"/><div class="connectionTitle">' . $itemName . '</div>
								</div></a>');
							}
						}
						echo('</div>');
						mysqli_close($conn3);
					 	?>
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
var cookieArray = [];
	
	/*====Read====*/
	var sizes = [];
	var i = 0;
	var rid = -1;
	var ric = -1;
	
	if (jQuery.cookie("cartItem")) {
		var cookieval = $.cookie("cartItem");
		
		 while(i==0){
		 if(cookieval!=""){
			var start_pos =  0;
			var end_pos =  cookieval.indexOf('|',start_pos);
			var text_to_get =  cookieval.substring(start_pos,end_pos)
			cookieArray.push(text_to_get);
			cookieval =  cookieval.substring(end_pos+1);
		 }
		 else {
		 	i++;
		 }
	 }
	// alert(cookieArray);
	var icint = 0;
	for (var j = 0; j < cookieArray.length; j++) {
	sizes = [];
	i = 0;
	rid = -1;
	ric = -1;
    
   
	var cookieval = cookieArray[j];
	
	
	var end_pos = cookieval.indexOf('[');
	var rid = cookieval.substring(0,end_pos);
	
	i=0;
	while(i==0){
		var start_pos =  cookieval.indexOf('[');
		var end_pos =  cookieval.indexOf(']',start_pos)+1;
		if (end_pos <= 0)
		{
			i++;
		} else {	
			var text_to_get =  cookieval.substring(start_pos,end_pos)
			sizes.push(text_to_get);
			 // alert(text_to_get);
			 cookieval =  cookieval.substring(end_pos);
		}
		
	}
	ric =  cookieval;
	ric = ric.substring(3);
	icint += parseInt(ric);
	 $('#cart-count').html(icint);
	}
 } else {
 	$('#cart-count').html("0");
 }
}
$('.no-item-cart').click(function() {
	alert("Ten produkt jest w tej chwili niedostępny")
});

$('.item-cart').click(function() {
	var cookieArray = [];
	
	
	/*====Read====*/
	var sizes = [];
	var i = 0;
	var rid = -1;
	var ric = -1;
	
	if (jQuery.cookie("cartItem")) {
		var cookieval = $.cookie("cartItem");
		
		 while(i==0){
		 if(cookieval!=""){
			var start_pos =  0;
			var end_pos =  cookieval.indexOf('|',start_pos);
			var text_to_get =  cookieval.substring(start_pos,end_pos)
			cookieArray.push(text_to_get);
			cookieval =  cookieval.substring(end_pos+1);
		 }
		 else {
		 	i++;
		 }
	 }
	 //alert(cookieArray+"|");
	
	for (var j = 0; j < cookieArray.length; j++) {
	sizes = [];
	i = 0;
	rid = -1;
	ric = -1;
    
   
	var cookieval = cookieArray[j];
	
	
	var end_pos = cookieval.indexOf('[');
	var rid = cookieval.substring(0,end_pos);
	
	i=0;
	while(i==0){
		var start_pos =  cookieval.indexOf('[');
		var end_pos =  cookieval.indexOf(']',start_pos)+1;
		if (end_pos <= 0)
		{
			i++;
		} else {	
			var text_to_get =  cookieval.substring(start_pos,end_pos)
			sizes.push(text_to_get);
			 // alert(text_to_get);
			 cookieval =  cookieval.substring(end_pos);
		}
		
	}
	ric =  cookieval;
	// alert(rid+sizes+ric);
	}
	
	/*====WRITE====*/
	var eq = 0;
	var id = $(this).attr("id");
	var names = [];
	var values = [];
	var count = 0;
	var ic = 1;

	var word=id.toString();
	$('.title').each(function(){
	  names.push($(this).html());
	  count ++;
	});
	
	$('.size-sel').each(function(){
	    values.push($(this).val());
	});

	for (var i = 0; i < count; i++) {
		word+="["+names[i]+values[i]+"]"
	}
	var eq = 0;
	for (var j = 0; j < cookieArray.length; j++) {
		var cv = cookieArray[j];
		if (cv.indexOf(word) >= 0)
		{
			eq=1;
			var nr =cv.indexOf("ic");
			var nr2 = cv.substring(nr+3);
			nr2 = parseInt(nr2);
			nr2+=1;
			cookieArray[j] = cv.substring(0,nr+3)+nr2;
		} else {
		}
	}
	if(eq==0){
		
		cookieArray.push(word+"ic=1");
	}
	var val= "";
	for (var j = 0; j < cookieArray.length; j++) {
		val += cookieArray[j]+"|";
		alert(val);
	}
	
	$.cookie("cartItem", val, { expires: 1, path: '/' });
} else {
	var eq = 0;
	var id = $(this).attr("id");
	var names = [];
	var values = [];
	var count = 0;
	var ic = 1;

	var word=id.toString();
	$('.title').each(function(){
	  names.push($(this).html());
	  count ++;
	});
	
	$('.size-sel').each(function(){
	    values.push($(this).val());
	});

	for (var i = 0; i < count; i++) {
		word+="["+names[i]+values[i]+"]"
	}
	word+="ic="+ic+"|";
	$.cookie("cartItem", word, { expires: 1, path: '/' });
}
alert("Produkt został dodany do koszyka");
var count = $('#cart-count').html();
count=parseInt(count) + 1;
$('#cart-count').html(count);
	
});
</script>

