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
    	<div class="sub-menu-container">
    		<div class="gender-picker">
    			<div class="gdiv" id="female">
	    			<p class="genderp">dla dziewczynki</p>
					<!-- <img src="img/girl.png" alt="Smiley face"> -->
				</div>
				<div class="gdiv" id="male">
					<!-- <img src="img/boy.png" alt="Smiley face"> -->
					<p class="genderp">dla chłopca</p>
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
					echo("<a href='index.php?category=".$rec['urlName']."-4'><div class='menu-div'>".$rec['name']."</div></a></li>");
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
					echo("<a href='index.php?category=".$rec['urlName']."-5'><div class='menu-div'>".$rec['name']."</div></a></li>");
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
	$.urlParam = function(name){
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
	}
	
	var namee = $.urlParam("category");
	
	if(namee==null){
		namee = '<?php echo($cat); ?>';
	}
	
	//if(name=="dla_dziewczynki")
	if ( (namee.indexOf("dla_dziewczynki") >= 0) || (namee.indexOf("-4") >= 0) ) {
		$("#sub-menu-boy").css("display","none");
		$("#sub-menu-girl").css("display","block");
		$("#female").css("color","#C99C57");
		$("#male").css("color","#58300c");
	}
	
	//if(name=="dla_chlopca")
	if ( (namee.indexOf("dla_chlopca") >= 0) || (namee.indexOf("-5") >= 0) ) {
		$("#sub-menu-boy").css("display","block");
		$("#sub-menu-girl").css("display","none");
		$("#male").css("color","#C99C57");
		$("#female").css("color","#58300c");
	}

	$("#female").click(function(){
		window.location.href = "index.php?category=dla_dziewczynki";
	});
	$("#male").click(function(){
		window.location.href = "index.php?category=dla_chlopca";
	});
</script>