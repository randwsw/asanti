<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
       
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>
 
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    <script type="text/javascript" src="js/jquery-cookie.js"></script>
    
    <!-- lightbox -------------------------------------->
    <script src="js/jquery-1.10.2.min.js"></script>
	<script src="js/lightbox-2.6.min.js"></script>
	<link href="css/lightbox.css" rel="stylesheet" />
    <!-- -------------------------------------------- -->
    
    <link rel="stylesheet" href="css/shopstyle.css" />
    <link rel="stylesheet" href="css/sliderstyle.css" />
    <link rel="stylesheet" href="css/bigdiv.css" />
   
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
        <!-- Include submenu -------------------------------------------------------- -->
        <!-- <?php include 'include/submenu.php'; ?> -->
        <!-- ------------------------------------------------------------------------ -->
       
 
		<div id="gallery">
			<div class="bigdiv">
				<div class="rowdiv" id="topdiv"></div>
				<div class="rowdiv" id="middiv">
				<!-- Original texture used: http://subtlepatterns.com/wave-grind/ -->
					<div class="rightdiv" id="midrightdiv"></div>
					<div class="centerdiv" id="midcenterdiv">
	    			<div id="title">Nasza Galeria</div>
	    			<div id="container">
	    				<?php if(!isset($_GET['action'])){ 
	    				$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
	    				
						
						if (mysqli_connect_errno())
							{
						 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
							}
						
						
						
						$result1 = mysqli_query($conn,"SELECT g.id AS id, g.title AS title, gp.url AS url FROM gallery g, gallery_photos gp WHERE g.id = gp.gallery_id AND orderN = 1");
						
						while($row1 = mysqli_fetch_array($result1))
						{
							echo('<a href="gallery.php?action=show&id=' . $row1['id'] . '">
									<div class="box">
		    							<img src="' . $row1['url'] . '" class="boxImg" />
		    							<div class="title">' . $row1['title'] . '</div>
		    						</div>
		    					</a>');
						}
							
						mysqli_close($conn);
	    				} ?>
	    				
	    				
	    				
	    				
	    				<?php if(isset($_GET['action']) && $_GET['action'] == "show"){
	    				
						$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
	    				$id = $_GET['id'];
						
						if (mysqli_connect_errno())
							{
						 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
							}
						
						
						
						$result1 = mysqli_query($conn,"SELECT gp.id AS id, gp.url AS url, g.title AS title FROM gallery_photos gp, gallery g WHERE gp.gallery_id = $id AND gp.gallery_id = g.id");
						
						while($row1 = mysqli_fetch_array($result1))
						{
							$id = $row1['id'];
							$url = $row1['url'];
							$title = $row1['title'];
							
							echo('<div class="outer"><div class="inner"><a href="' . $url . '" data-lightbox="' . $title . '" >
									<img src="' . $url . '" class="galleryImg" id="' . $id . '"/>
								</a></div></div>');
				
							// echo('<a href="gallery.php?action=show&id=' . $row1['id'] . '">
									// <div class="box">
		    							// <img src="' . $row1['url'] . '" class="boxImg" />
		    							// <div class="title">' . $row1['title'] . '</div>
		    						// </div>
		    					// </a>');
						}
							
						mysqli_close($conn);	
	    				
	    					
	    				} ?>
	    			</div>
	    			</div>
				</div>
				<div class="leftdiv" id="midleftdiv"></div>
			</div>
			<div class="rowdiv" id="botdiv"></div>
		</div>
	</div>
</body>      
</html>
<script type="text/javascript">

function resizeImg(){
	$("div #gallery #container .outer img").each(function(){
			if ($(this).attr("complete")) {
				var width = $(this).width();
		        var height = $(this).height();
		    } else {
		        $(this).load(function(){
		            var width = $(this).width();
		        	var height = $(this).height();
		        	// alert("width: " + width + ";height: " + height);
		        	if(width > height){
				    	$(this).removeClass();
				    	$(this).addClass("galleryImgW");
				    }else{
				    	$(this).removeClass();
				    	$(this).addClass("galleryImgH");
				    }
		        });
		    }

        });
}


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
$(window).load(function() {
       
        $.post("controllers/autoRemember.php",
       { email: $.cookie("rememberme")})
                .done(function(data) {
                }
        );
         
       
});
 
$( document ).ready(function() {
	
		resizeImg();
        	
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
        if(jQuery.inArray($(this).attr('id'), cookieArray)!=-1) {
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