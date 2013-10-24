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
	<div class="bg">
	</div> 
	
	
    <div class="container">
	<!-- Include submenu -------------------------------------------------------- -->
	<?php include 'include/submenu.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	

	<?php
	
	$itemsPerPage = 12;
	
	 echo ('<style type="text/css">
        #page_'.$page.' {
            color: #996515;
        }
        </style>'
		);
			// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
		
		$sql = "SET NAMES 'utf8'";
		!mysqli_query($conn,$sql);
				
		// Check connection
		if (mysqli_connect_errno())
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		$lol = explode("-", $cat, 2);
		$newcat = $lol[0];
		 //echo($newcat);
		// echo($cat);
		
		if($cat!='recommended')
		{		
			$sql2= mysqli_query($conn, "SELECT count(*) AS count FROM item i, photo ph, category c, category_con cc WHERE i.headPhotoId = ph.id AND i.active = 1 AND ( c.urlName ='$newcat' OR c.parentId = (SELECT id FROM category 
			WHERE urlName='$newcat') ) AND cc.item_id = i.id AND cc.cat_id =c.id;") or die(mysql_error());
		}
		else {
			$sql2= mysqli_query($conn, "SELECT count(*) AS count FROM item i, photo ph, recommended r WHERE i.headPhotoId = ph.id AND i.active = 1 AND i.id = r.item_id;") or die(mysql_error());	
		}
		
		while($rec2 = mysqli_fetch_array($sql2)) {
			$count =  $rec2['count'];
		}
		
		$pages=1;
		for ($i = 1; $i <= $count; $i++) {
		    if($i%$itemsPerPage==0){
		    	$pages++;
		    }
		}
		$nextpage = $page+1;
		$prevpage = $page+-1;
		if(($page==1)||($page==$pages)){
			$edge = 2;
		} else if(($page==2)||($page==$pages-1)){
			$edge = 2;
		} else {
			$edge = 0;
		}
		
		$order = 'az';
		if(isset($_GET['order'])) {
			$order = $conn->real_escape_string($_GET['order']);
		}
		$ordername='';
		$orderdb='';
		switch ($order) {
		    case "az":
		        $ordername = 'Alfabetycznie: a - z';
		        $orderdb='i.name ASC, price ASC';
		        break;
		    case "za":
		        $ordername = 'Alfabetycznie: z - a';
		        $orderdb='i.name DESC, price ASC';
		        break;
		    case "pa":
		        $ordername = 'Cena: od najmniejszej';
		        $orderdb='price ASC, i.name ASC';
		        break;
			case "pd":
		        $ordername = 'Cena: od największej';
				$orderdb='price DESC, i.name ASC';
		        break;
		}
		
		echo("<div class='itemMenu'>");
		//echo("<div class='sortBy'><a>Sortuj: </a><a href='index.php?category=".$cat."&page=$page&order=az' >alfabetycznie, </a><a href='index.php?category=".$cat."&page=$page&order=pa' >po cenie</a></div>");
		echo("<div class='sortBy'>
		<a>Sortuj: $ordername</a>	    
	    <ul>
	    	<li><a class='ele' href='index.php?category=".$cat."&page=$page&order=az'>Alfabetycznie: a - z</a></li>
	        <li><a class='ele' href='index.php?category=".$cat."&page=$page&order=za'>Alfabetycznie: z - a</a></li>
	        <li><a class='ele' href='index.php?category=".$cat."&page=$page&order=pa'>Cena: od najmniejszej</a></li>
	        <li><a class='ele' href='index.php?category=".$cat."&page=$page&order=pd'>Cena: od największej</a></li>	        
	    </ul> 
		</div>");

		echo("<div class='pages'>"); 
		if($page>1)
		echo("<a href='index.php?category=".$cat."&page=$prevpage&order=$order'>&#171</a> ");
			for ($i = $page-(2+$edge); $i <= $page+(2+$edge); $i++) {
				if(($i>0)&&($i<=$pages))
				{
				echo("<a id='page_$i' href='index.php?category=".$cat."&page=$i&order=$order'>".$i."</a> ");
				}
			}
		if($page<$pages)
		echo("<a href='index.php?category=".$cat."&page=$nextpage&order=$order'>&#187</a> ");
		echo("</div>");
		echo("</div>");
	?>

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
	echo("<div class='title'>Polecamy</div>");
	?>
	<div class="products">
		<?php
		

		$min = $itemsPerPage*($page-1);
		$max = $min+4;
		$icount = 0;
		
		if($cat!='recommended')
		{
			$sql= mysqli_query($conn, "SELECT i.name AS iname, price, url, i.id FROM item i, photo ph, category c, category_con cc WHERE i.headPhotoId = ph.id AND i.active = 1 AND ( c.urlName ='$newcat' OR c.parentId = (SELECT id FROM category 
			WHERE urlName='$newcat') ) AND cc.item_id = i.id AND cc.cat_id =c.id ORDER BY $orderdb limit $min, $itemsPerPage;") or die(mysql_error());
		}else {
			$sql= mysqli_query($conn, "SELECT i.name AS iname, price, url, i.id, c.urlName AS urlName, c.parentId AS parentId FROM category c, category_con cc, item i, photo ph, recommended r WHERE i.id = cc.item_id AND c.id = cc.cat_id AND i.headPhotoId = ph.id AND i.active = 1 AND i.id = r.item_id;") or die(mysql_error());		
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
		    
		    <div class='product-price'>
	 			<p>".$rec['price']."zł</p>
	 		</div>
		    <div class='product-name'>
	 			<p>".$rec['iname']."</p>
	 		</div>");
			
			if($icount==1){
				echo("<div class='imageConBig' id='topleft'>");
			} else if($icount==4){
				echo("<div class='imageConBig' id='topright'>");
			} else if($icount%4==1 && $icount+4>$itemsPerPage){
				echo("<div class='imageConBig' id='botleft'>");
			} else if($icount==$itemsPerPage){
				echo("<div class='imageConBig' id='botright'>");
			} else if($icount<4){
				echo("<div class='imageConBig' id='top'>");
			}
			else if($icount+4>$itemsPerPage){
				echo("<div class='imageConBig' id='bot'>");
			}
			else if($icount%4==1){
				echo("<div class='imageConBig' id='left'>");
			}
			else if($icount%4==0){
				echo("<div class='imageConBig' id='right'>");
			}  
			else {
				echo("<div class='imageConBig' id='middle'>");
			}
			echo("
				<a href='item.php?id=".$rec['id']."' ><img class='productImageBig' src='".$rec['url']."' alt='Smiley face' ></a>
		    	</div>
		    </div>");
			
		  	
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
<?php
echo("<div class='itemMenu'>");
		echo("<div class='pages'>"); 
		if($page>1)
		echo("<a href='index.php?category=".$cat."&page=$prevpage'>&#171</a> ");
			for ($i = $page-(2+$edge); $i <= $page+(2+$edge); $i++) {
				if(($i>0)&&($i<=$pages))
				{
				echo("<a id='page_$i' href='index.php?category=".$cat."&page=$i'>".$i."</a> ");
				}
			}
		if($page<$pages)
		echo("<a href='index.php?category=".$cat."&page=$nextpage'>&#187</a> ");
		echo("</div>");
		echo("</div>");	 
?>
     </div> 
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
	    $(".menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
	    
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
$('.cart').click(function() {
	
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