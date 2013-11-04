<?php if(!session_id()) { session_start();} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    <link rel="stylesheet" href="css/borders/storeborders.css" />
    
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
	<?php
    	// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
		
		$cat = 'recommended';
		if(isset($_GET['category'])) {
		    //$cat = $_GET['category'];
			$cat = $conn->real_escape_string($_GET['category']);
			
		} else if(isset($_GET['id'])) {
			$aid = $_GET['id'];
			$sql= mysqli_query($conn, "SELECT c.name, c.urlName AS un, c.parentId FROM category c , category_con cc WHERE cc.item_id = $aid AND cc.cat_id = c.id ") or die(mysql_error());
			while($rec = mysqli_fetch_array($sql)) {
				$pi = $rec['parentId'];
				$un = $rec['un'];
			}	
			$cat = $un.'-'.$pi;
		}
		
		$page = 1;
		if(isset($_GET['page'])) {
			$page = $conn->real_escape_string($_GET['page']);
		}
	?>
	<div class="bg">
	</div> 
	
    <div class="container">
	
	<?php
	$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

	if (mysqli_connect_errno())
		{
	 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	
	
	$result = mysqli_query($conn,"SELECT urlName FROM category WHERE id = '1'");
	while($row = mysqli_fetch_array($result))
	{
		$girls = $row['urlName'];
	}
	
	$result2 = mysqli_query($conn,"SELECT urlName FROM category WHERE id = '2'");
	while($row2 = mysqli_fetch_array($result2))
	{
		$boys = $row2['urlName'];
	}
	?>
	

	
	<style>@import url(css/borders/indexborders.css);</style>
	<div class="bigdivm">
		<div class="rowdivm" id="topdivm">
		</div>
		<div class="rowdivm" id="middivm">
			<div class="rightdivm" id="midrightdivm">
				
			</div>
			<div class="centerdivm" id="midcenterdivm">
				<div id="welcome">
					<div id="title">
						<h>Witamy w sklepie Asanti for kids</h>
					</div>
					<div id="subTitle">
						<p>Zapraszamy do zakupów!</p>
					</div>
					<div id="pickMenu">
						<a href="store.php?category=<?php echo $girls; ?>"><div id="p1"></div></a>
						<a href="store.php?category=<?php echo $boys; ?>"><div id="p2"></div></a>
					</div>
				</div>
			</div>
			<div class="leftdivm" id="midleftdivm">
				
			</div>
		</div>
		<div class="rowdivm" id="botdivm">
		</div>
	</div>

	
	<div class="bigdiv">
		<div class="rowdiv" id="topdiv">
		</div>
		<div class="rowdiv" id="middiv">
			<!-- Original texture used: http://subtlepatterns.com/wave-grind/ -->
			<div class="rightdiv" id="midrightdiv">
				
			</div>
			<div class="centerdiv" id="midcenterdiv">
    <div class="recommended">
    <?php
    if($cat=='recommended')
	echo("<div class='title'><a href='promo.php'>Polecamy<a></div>");
	?>
	<div class="products">
		<?php
		
		$icount = 0;
		$recarray = array();
		
		$sql= mysqli_query($conn, "SELECT item_id, price FROM recommended r, rec_price rp WHERE r.id = rp.rec_id");
		while($rec = mysqli_fetch_array($sql)) {
			$recarray[$rec["item_id"]] = $rec["price"];
		}
		
		if($cat!='recommended')
		{
			$sql= mysqli_query($conn, "SELECT a.price, a.id, a.url, a.urlName, a.parentId, a.iname, it.price AS itprice FROM
(SELECT i.name AS iname, url, i.id, c.urlName AS urlName, c.parentId AS parentId, rp.price AS price 
FROM item i, photo ph, category c, category_con cc, recommended r, rec_price rp 
WHERE i.headPhotoId = ph.id AND i.active = 1 AND ( c.urlName ='$newcat' OR c.parentId = (SELECT id FROM category WHERE urlName='$newcat') ) AND cc.item_id = i.id AND cc.cat_id=c.id AND r.item_id = i.id AND rp.rec_id = r.id 
UNION ALL
SELECT i.name AS iname, url, i.id, c.urlName AS urlName, c.parentId AS parentId, i.price AS price 
FROM item i, photo ph, category c, category_con cc
WHERE i.headPhotoId = ph.id AND i.active = 1 AND ( c.urlName ='$newcat' OR c.parentId = (SELECT id FROM category WHERE urlName='$newcat') ) AND cc.item_id = i.id AND cc.cat_id=c.id AND i.id NOT IN (SELECT item_id FROM recommended) ) a, item it
WHERE it.id=a.id
ORDER BY price ASC, iname ASC limit 0, 4;") or die(mysql_error());
		}else {
			$sql= mysqli_query($conn, 
			"SELECT i.name AS iname, url, i.id, c.urlName AS urlName, c.parentId AS parentId, rp.price AS price, i.price AS iprice 
			 FROM rec_price rp, category c, category_con cc, item i, photo ph, recommended r 
			 WHERE i.id = cc.item_id AND c.id = cc.cat_id AND i.headPhotoId = ph.id AND i.active = 1 AND i.id = r.item_id AND r.id = rp.rec_id
			 ORDER BY price ASC, iname ASC limit 0, 4;") or die(mysql_error());	
			 
			 		
		}
		
		while($rec = mysqli_fetch_array($sql)) {
			$icount++;
			if($cat=='recommended') {
				
				$cat=$rec['urlName']."-".$rec['parentId'];
			}
			echo("<div class='product-info'>");
			
			echo("
	     	<div class='imageContainer'>
				<!--<div class='imageOverlay' id='item_".$rec['id']."' >
					<a href='item.php?id=".$rec['id']."' >
						<div class='eye'></div>
					</a>
					<div class='cart' id='item_".$rec['id']."'></div>	 	    	
		    	</div>	-->	    	
		     	<img class='productImage' src='".$rec['url']."' alt='Smiley face' >
		    </div>
		    
		    <div class='product-name'>
	 			<p>".$rec['iname']."</p>
	 		</div>
		    <div class='product-price'>");
			   if(isset($recarray[$rec['id']])){
			   	if(isset($rec['itprice'])) {
			   		echo("
			   		<p><strike id='pstrike'>".$rec['itprice']."zł</strike></p>
		 			<p class='promo'>".$recarray[$rec['id']]."zł</p>");
			   	} else {
			   		echo("
			   		<p><strike id='pstrike'>".$rec['iprice']."zł</strike></p>
		 			<p class='promo'>".$recarray[$rec['id']]."zł</p>");
			   	}
			   		
			   } else {
			   		echo("<p>".$rec['price']."zł</p>
					<p class='promo'></p>");
			   }
	 			
	 		echo("</div>
	 		");
			
			if($icount==1){
				echo("<div class='imageConBig' id='topleft' ");?>onclick="window.location.href='item.php?id=<?php echo($rec["id"]); ?>'" style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			} else if($icount==4){
				echo("<div class='imageConBig' id='topright'");?>onclick="window.location.href='item.php?id=<?php echo($rec["id"]); ?>'" style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			} else if($icount%4==1 && $icount+4>4){
				echo("<div class='imageConBig' id='botleft'");?>onclick="window.location.href='item.php?id=<?php echo($rec["id"]); ?>'" style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			} else if($icount==4){
				echo("<div class='imageConBig' id='botright'");?>onclick="window.location.href='item.php?id=<?php echo($rec["id"]); ?>'" style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			} else if($icount<4){
				echo("<div class='imageConBig' id='top'");?>onclick="window.location.href='item.php?id=<?php echo($rec["id"]); ?>'" style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			}
			else if($icount+4>4){
				echo("<div class='imageConBig' id='bot'");?>onclick="window.location.href='item.php?id=<?php echo($rec["id"]); ?>'" style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			}
			else if($icount%4==1){
				echo("<div class='imageConBig' id='left'");?>onclick="window.location.href='item.php?id=<?php echo($rec["id"]); ?>'" style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			}
			else if($icount%4==0){
				echo("<div class='imageConBig' id='right'");?>onclick="window.location.href='item.php?id=<?php echo($rec["id"]); ?>'" style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			}  
			else {
				echo("<div class='imageConBig' id='middle'");?>style="background:url('<?php echo($rec['url']); ?>') no-repeat " <?php echo(">");
			}
			echo("
				
		    	</div>
		    ");
		    echo("</div>");
			
		  	
		} 
		
		
		
		mysqli_close($conn);
		
		?>
     	 
     </div>  
</div>
<div id="more">
	<a href='promo.php'>więcej</a>
</div>
</div>
			<div class="leftdiv" id="midleftdiv">
				
			</div>
		</div>
		<div class="rowdiv" id="botdiv">
		</div>
	</div>
     </div>
     <?php include 'include/footer.php'; ?> 
</body>      
</html>
<script type="text/javascript">

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

// $(window).load(function() {
// 	
	// $.post("controllers/autoRemember.php", 
        // { email: $.cookie("rememberme")})
		// .done(function(data) {
		// }
	// );
// 	 
// 	
// });

$( document ).ready(function() {
	checkCart();
	
	var height = $( '#midcenterdiv' ).css( "height" );
	 $( '#midrightdiv, #midleftdiv, #middiv' ).css( "height", height );
	
	$(function() {			
	    // $(".menu-anim").lavaLamp({
	        // fx: "backout",
	        // speed: 700,
	    // });
	    
	    $("#sub-menu-anim-b").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
	    
	    $("#sub-menu-anim-g").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
	});	
});

// $('.imageContainer').mouseenter(function() {
   // $(this).children('.imageOverlay').fadeIn(100, function() {
   		// $(this).children('.imageOverlay').stop();
   // });
// });
// 
// $('.imageContainer').mouseleave(function() {
	// $(this).children('.imageOverlay').fadeOut(100, function() {
		// $(this).stop();
// });
// });

// function setCookie(name, value, expire) {
        // document.cookie = name + "=" + escape(value) + ((expire==null)?"" : ("; expires=" + expire.toGMTString()))
// }
// 
// $('.cart').click(function() {
// 	
	// var cookieArray = [];
	// if (jQuery.cookie("cartItem")) {
		// var cookieval = $.cookie("cartItem");
		// cookieval+=",";
		// var item="";		
		// for (var i=0; i < cookieval.length; i++) {
			// if(cookieval.charAt(i)!=',')
			// {
				// item += cookieval.charAt(i);
			// }
			// else
			// {
				// cookieArray.push(item);
				// item="";
			// }
		// }
	// }
	// else{		
	// }
	// if(jQuery.inArray($(this).attr('id'), cookieArray)!=-1)	{
			// var id = jQuery.inArray($(this).attr('id'), cookieArray);
			// //alert(id);
			// // cookieArray[id]+="#2#"
		// }
	// else {
		// // cookieArray.push($(this).attr('id'));
	// }
// 	
	// cookieArray.push($(this).attr('id'));	
	// $.cookie("cartItem", cookieArray, { expires: 1, path: '/' });
	// alert("Produkt został dodany do koszyka");
	// checkCart();
// });


</script>