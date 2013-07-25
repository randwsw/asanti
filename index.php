<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - chwytliwe hasło</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>

    
    <link rel="stylesheet" href="css/indexstyle.css" />
    <link rel="stylesheet" href="css/sliderstyle.css" />   
</head>

<body>

	   <ul class="cb-slideshow">
            <li><span>Image 01</span><div><h3>Asanti</h3></div></li>
            <li><span>Image 02</span><div><h3>Costam</h3></div></li>
            <li><span>Image 03</span><div><h3>Cos innego</h3></div></li>
            <li><span>Image 04</span><div><h3>lolololol</h3></div></li>
            <li><span>Image 05</span><div><h3>rofl</h3></div></li>
            <li><span>Image 06</span><div><h3>xd</h3></div></li>
        </ul>
        
	<div class="menu">
                
		<ul class="menu-anim">
                
                    <li class="current" id="asanti"></li>
                    <li class="other"><a href="contact.html"><div class="menu-div">KONTAKT</div></a></li>                                        
                    <li class="other"><a href="about.html"><div class="menu-div">O NAS</div></a></li>
                    <li class="other"><a href="shop.php"><div class="menu-div">SKLEP</div></a></li>
                    
		</ul>    
            
	</div> 
      
</body>      
</html>
<script type="text/javascript">

	$(function() {			
	    $(".menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
			
		var $tlt = jQuery.noConflict();
		$tlt(function() {
		$tlt('.lavamenu').lavalamp();
	});
});

</script>

