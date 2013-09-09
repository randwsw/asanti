<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script src="js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    <script type="text/javascript" src="js/jquery.watermark.min.js"></script>
    <script type="text/javascript" src="js/jquery-cookie.js"></script>
    
    <link rel="stylesheet" href="css/shopstyle.css" />
    <link rel="stylesheet" href="css/sliderstyle.css" />
    
</head>
<body>
	<div class ="container">
	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
		<div class="product-row" id="top-row">
			<div class="column-name">
				<p>Nazwa produktu</p>
			</div>
			<div class="column-price">
				<p>Cena za sztukę</p>
			</div>
			<div class="column-quantity">
				<p>Ilość</p>
			</div>
			<div class="column-price-all">
				<p>Cena całkowita</p>
			</div>
			<div class="column-remove">
				<p>Usuń</p>
			</div>
		</div>
		
		<!-- <div class="product-row" id="middle-row">
			<div class="column-name">
				<p>Produkt1</p>
			</div>
			<div class="column-price">
				<p>24,99</p>
			</div>
			<div class="column-quantity">
				<input type="text"/>
			</div>
			<div class="column-price-all">
				<p>24,99</p>
			</div>
			<div class="column-remove">
				<a href=""><p>X</p></a>
			</div>
		</div>
		
		<div class="product-row" id="middle-row">
			<div class="column-name">
				<p>Produkt2</p>
			</div>
			<div class="column-price">
				<p>34,99</p>
			</div>
			<div class="column-quantity">
				<input type="text"/>
			</div>
			<div class="column-price-all">
				<p>34,99</p>
			</div>
			<div class="column-remove">
				<a href=""><p>X</p></a>
			</div>
		</div> -->
		<?php
		$sum=0; 
		if(isset($_COOKIE['cartItem']))
        {
        		$querypart="";
                $var = $_COOKIE['cartItem'];
				$var = $var.',';
				$item="";
				$arr = array();
				$test = array();
				
                for ($i = 0; $i<strlen($var); $i++)  {
				    $character = substr($var, $i,1);
					if($character != ',')
					{
						$item= $item.$character;						
					}
					else {						
						$item = substr($item, 5);
						if (in_array($item, $arr, true)) {
							$test[$item]+=1;				 
						}
						else{
							$test[$item]=1;
							$querypart= $querypart."i.id = ".$item." OR ";  			 	
						}
						array_push($arr,$item);

						$item="";
					}
				}
				
			$querypart = substr($querypart,0,-3);
			// echo($querypart);
			// print_r($arr);
			// print_r($test);
			
			// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
			$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
			// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
					
			// Check connection
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
		  	 //echo("SELECT i.name AS iname, value, i.id FROM item i, price pr WHERE ".$querypart.";");
			
			$sql= mysqli_query($conn, "SELECT i.name AS iname, price, i.id FROM item i WHERE ".$querypart.";");
			
			
			echo("<form method='POST' action='confirm.php' name='cartForm'>");
			while($rec = mysqli_fetch_array($sql)) {
				$sum+= $rec['price']*$test[$rec['id']];
				echo(
				"<div class='product-row' id='middle-row'>
					<div class='column-name'>
						<p>".$rec['iname']."</p>
						<input type='hidden' value='".$rec['iname']."' name='name[]'>
					</div>
					<div class='column-price'>
						<p id=price-".$rec['id'].">".$rec['price']."</p>
						<input type='hidden' value='".$rec['price']."' name='price[]'>
					</div>
					<div class='column-quantity'>
						<input type='text' value='".$test[$rec['id']]."' id='quantity".$rec['id']."' class='quantityTb' name='quantity[]'/>
					</div>
					<div class='column-price-all'>
						<p id=price-all-".$rec['id'].">".$rec['price']*$test[$rec['id']]."</p>
					</div>
					<div class='column-remove' id='column-remove-".$rec['id']."'>
						<a href=''><p>X</p></a>
					</div>
				</div>"
				);
			}
		 
        }
		else
			{
				echo("<div id='emptyCart'><p>Nie dodano jeszcze żadnego produktu</p></div>");
			}
		?>
		<!-- <div class="product-row" id="bot-row">
			<div class="sum">
					<p>Suma:</p>
			</div>
			<div class="sum-money">
				<p><a>Zapłać</a> </p>
			</div>
			<div class="sum-money">
					<p><?php echo($sum.".00"); ?></p>
			</div>
		</div> -->
		<div class="product-row" id="bot-row">
			<div class="column-name">
				<p>Razem do zapłaty:</p>
			</div>
			<div class="column-price-all">
				<p id='complPrice'><?php echo($sum); ?></p>
			</div>
			<div class="column-remove">
				<p><a id='cartSubmit' onclick="document.cartForm.submit();">Kup</a></a></p>
			</div>
		</div>
		</form>
	</div>
</body>
</html>
<script type="text/javascript">

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

$( document ).ready(function() {


	$(function() {			
	    $(".menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
			
	});
	var x = 0;
	var y = 0;
	$( ".quantityTb" ).change(function() {
		var res = $("#price-"+x).html()*$(this).attr("value");
		var add = $("#price-"+x).html()*y;
		$("#price-all-"+x).html(res);
		var cp = $("#complPrice").html() - add + res;
		$("#complPrice").html(cp);
	});

	$( ".quantityTb" ).focus(function() {
		x = $(this).attr("id");
		y = $(this).attr("value");
		x = x.substring(8);
	});
	
});



$('.column-remove').click(function() {
	var remItem = "item_"+$(this).attr('id').substring(14);
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
				if(item!=remItem)
				{
					cookieArray.push(item);
				}
				item="";
			}
		}
	}
	else{
	}
	$.cookie("cartItem", cookieArray, { expires: 0, path: '/' });
	
	if (cookieArray.length > 0) {
		$.cookie("cartItem", cookieArray, { expires: 1, path: '/' });
		alert("Produkt zostanie usunięty z koszyka");
	}
	//)
	//checkCart();
	
});
</script>