    <?php
    	// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
		
		$cat = 'recommended';
		if(isset($_GET['category'])) {
		    //$cat = $_GET['category'];
			$cat = $conn->real_escape_string($_GET['category']);
		}
		
		$page = 1;
		if(isset($_GET['page'])) {
			$page = $conn->real_escape_string($_GET['page']);
		}
	?>
    	<div class="sub-menu-container">
    		<div class="gender-picker">
    			<div class="gdiv" id="female">
	    			<p class="genderp">Dziewczynka</p>
					<img src="img/female.png" alt="Smiley face">
				</div>
				<div class="gdiv" id="male">
					<img src="img/male.png" alt="Smiley face">
					<p class="genderp">Chłopiec</p>
				</div>
    		</div> 
		<div class="sub-menu" id="sub-menu-girl">
		                
			<ul class="sub-menu-anim" id="sub-menu-anim-g">
				<?php
				
				$sql = "SET NAMES 'utf8'";
				!mysqli_query($conn,$sql);
						
				// Check connection
				if (mysqli_connect_errno())
				  {
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				  }

				$sql= mysqli_query($conn, "SELECT name, urlName FROM category WHERE parentId=4 OR id=4;") or die(mysql_error());
				
				while($rec = mysqli_fetch_array($sql)) {
					if (strpos($cat, $rec['urlName']) !== false) :               
		        	echo("<li class='current'>");
					else :
		        	echo("<li class='other'>");
		        	endif;               
					echo("<a href='shop.php?category=".$rec['urlName']."-4'><div class='menu-div'>".$rec['name']."</div></a></li>");
					}		
				?>
					
			</ul>                
		</div>
		<div class="sub-menu" id="sub-menu-boy">
		                
			<ul class="sub-menu-anim" id="sub-menu-anim-b">
				
				<?php
				$sql= mysqli_query($conn, "SELECT name, urlName FROM category WHERE parentId=5 OR id=5;") or die(mysql_error());
				
				while($rec = mysqli_fetch_array($sql)) {
					if (strpos($cat, $rec['urlName']) !== false) :               
		        	echo("<li class='current'>");
					else :
		        	echo("<li class='other'>");
		        	endif;               
					echo("<a href='shop.php?category=".$rec['urlName']."-5'><div class='menu-div'>".$rec['name']."</div></a></li>");
					}
				
				mysqli_close($conn);
									 
				?>
			</ul>                
		</div>
			<a href="cart.php">
			<div class="cart-div">
				<p class="cart-desc">Koszyk</p>
				<img class="cart-image" src="img/cart-big-dark.png" alt="Smiley face">
				<p class="item-count" id="cart-count">0</p>
			</div>
			</a>
		</div>

<script type="text/javascript">

	function getURLParameter(name) {
	    return decodeURI(
	        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
	    );
	}
	
	var name = getURLParameter("category");
	
	//if(name=="dla_dziewczynki")
	if ( (name.indexOf("dla_dziewczynki") >= 0) || (name.indexOf("-4") >= 0) ) {
		$("#sub-menu-boy").css("display","none");
		$("#sub-menu-girl").css("display","block");
		$("#female").css("color","#996515");
		$("#male").css("color","black");
	}
	
	//if(name=="dla_chlopca")
	if ( (name.indexOf("dla_chlopca") >= 0) || (name.indexOf("-5") >= 0) ) {
		$("#sub-menu-boy").css("display","block");
		$("#sub-menu-girl").css("display","none");
		$("#male").css("color","#996515");
		$("#female").css("color","black");
	}

	$("#female").click(function(){
		window.location.href = "shop.php?category=dla_dziewczynki";
	});
	$("#male").click(function(){
		window.location.href = "shop.php?category=dla_chlopca";
	});
</script>
