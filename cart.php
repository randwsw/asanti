<?php if(!session_id()) { session_start();} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->

    
</head>
<body>
	<div class="bg">
	</div>
	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<!-- Include background animation ------------------------------------------- -->
	<?php include 'include/backanim.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<div class ="container">
	<div class="cart-fill"></div>
		<div class="product-row" id="top-row">
			<div class="column-name">
				<p>Nazwa produktu</p>
			</div>
			<div class="column-size">
				<p>Rozmiary</p>
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
		
		
		<?php
		class cartItem
		{
		    public $sizes = array();
		    public $count = "";
			public $id = 0;
			function addSize($sizeItem)
			{
			    array_push($this->sizes, $sizeItem);
			}		
		}
		
		$cartItems = array();
		$querypart = "";
		
		
		$sum=0; 
		if(isset($_COOKIE['cartItem']))
        {
				$cookies = array();
				$item="";
				
                $var = $_COOKIE['cartItem'];	
				
                for ($i = 0; $i<strlen($var); $i++)  {
				    $character = substr($var, $i,1);
					if($character != '|')
					{
						$item= $item.$character;						
					}
					else {					
						array_push($cookies, $item);
						$item="";
					}
				 }
				
				
				
				for ($j = 0; $j < sizeof($cookies); $j++) {
				
				$citem = new cartItem();	
				
				$cookieval = $cookies[$j];
				$cookievalArray = str_split($cookies[$j]);
				
				$end_pos = array_search('[', $cookievalArray);
				$rid = substr($cookieval,5 , $end_pos-5);
				$citem->id=$rid;
				// echo($rid);
				// echo("<br>"); 
				
				$i=0;
				while($i==0){
					$start_pos = array_search('[', $cookievalArray);
					$end_pos =  array_search(']', $cookievalArray)+1; 
					//echo($start_pos."-".$end_pos);
					//echo("<br>");
					{	
						$text_to_get = substr($cookieval, $start_pos+1, $end_pos-($start_pos+2));
						$citem->addSize($text_to_get);
						//echo("TEXT TO GET: ".$text_to_get);
						//echo("<br>");
						 $cookieval = substr($cookieval, $end_pos);
						 //echo($cookieval."<br>");
						 $cookievalArray = str_split($cookieval);
					}
					if (array_search(']', $cookievalArray)==null) {
					    $i++;
					}
				}
				$citem->count = substr($cookieval, 3, $end_pos-3);
				array_push($cartItems, $citem);
				}
// 				
			
			// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
			$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
			// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
					
			// Check connection
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
		  	
		  	 $count = 0;
			 $globalcount = 0;
			foreach ($cartItems as $val) {
				
			$querypart = "i.id = ".$val->id;
			   			
			$sql= mysqli_query($conn, "SELECT i.name AS iname, i.price, i.id, urlName, parentId, rp.price AS rprice 
										FROM item i, category c, category_con cc, recommended r, rec_price rp 
										WHERE (".$querypart.") AND i.id=cc.item_id AND c.id=cc.cat_id AND i.id=r.item_id AND r.id=rp.rec_id;");

			if( mysqli_num_rows($sql) > 0) {
			$sql= mysqli_query($conn, "SELECT i.name AS iname, i.id, urlName, parentId, rp.price AS price 
										FROM item i, category c, category_con cc, recommended r, rec_price rp 
										WHERE (".$querypart.") AND i.id=cc.item_id AND c.id=cc.cat_id AND i.id=r.item_id AND r.id=rp.rec_id;");
			}
			else
			{
			$sql= mysqli_query($conn, "SELECT i.name AS iname, price, i.id, urlName, parentId FROM item i, category c, category_con cc WHERE (".$querypart.")AND i.id=cc.item_id AND c.id=cc.cat_id;");

			}
			
			echo("<form method='POST' action='confirm.php' name='cartForm'>");
			while($rec = mysqli_fetch_array($sql)) {
				$cat = $rec['urlName']."-".$rec['parentId'];
				$sum+= $rec['price']*$val->count;
				$globalcount += $val->count;
				echo(
				"<div class='product-row' id='middle-row'>
				<input type='hidden' value='".$rec['id']."' name='iid[]'>			
					<div class='column-name'>
						<p><a href='item.php?id=".$rec['id']."'>".$rec['iname']."</a></p>
						<input type='hidden' value='".$rec['iname']."' name='name[]'>
					</div>
					<div class='column-size'>");
					$s_ = '';
					foreach ($val->sizes as $s) {
						$s = str_replace(":", ": ", $s);
						$s_.=$s.' ';
						echo("<p>".$s."</p>");
					}
					echo("<input type='hidden' value='".$s_."' name='sizes[]'>");						
					echo("</div>
					<div class='column-price' id=pc".$count.">
						<p id=price-".$rec['id'].">".$rec['price']."</p>
						<input type='hidden' value='".$rec['price']."' name='price[]'>
					</div>
					<div class='column-quantity' id=cq".$count.">
						<input type='text' value='".$val->count."' id='quantity".$rec['id']."' class='quantityTb' name='quantity[]'/>
					</div>
					<div class='column-price-all'>
						<p class='price-all' id=price-all-".$rec['id'].">".$prc = number_format($rec['price']*$val->count, 2, '.', '')."</p>
						
					</div>
					<div class='column-remove' id='".$cookies[$count]."|'>
						<a href=''><p>X</p></a>
					</div>
				</div>"
				);
			}
			$count++;
		  }

		  $sql= mysqli_query($conn, "SELECT value, item_count FROM discount WHERE active = 1;");

		  $ex = 0;
		  $ct = 0;
		  $dc= 0;
		while($rec = mysqli_fetch_array($sql)) {
			$dc = $rec['value'];
			$ct = $rec['item_count'];
			$ex = 1;
		}
		$sum = number_format($sum, 2, '.','');
		if($ex==1){
			if($globalcount>=$ct) {
				$sum=$sum-($sum*$dc/100);
				$sum = number_format($sum, 2, '.', '');
			} 
		}				
		
		mysqli_close($conn);	
        }
		else
			{
				echo("<div id='emptyCart'><p>Nie dodano jeszcze żadnego produktu</p></div>");
			}
			
		
		?>
		<div class="product-row" id="bot-row">
			<div class="column-submit">
				<p><a href="index.php">wróć do sklepu</a></a></p>
			</div>
			<div class="column-name">
				<p>Razem do zapłaty:</p>
			</div>
				<div class="column-price-all">
					<p id='complPrice'>
						<?php					
						echo($sum);
	  					?>
					</p>
					<p id='disc'>
						<?php if($globalcount>=$ct) {
							echo("(-".$dc."%)");
						}
						?>
					</p>
			</div>
			<div class="column-submit">
				<p><a id='cartSubmit' onclick="document.cartForm.submit();">Kup</a></a></p>
			</div>
		</div>
		<input id='discounthid' type='hidden' name='dischid' value='<?php if($globalcount>=$ct) echo($dc); else echo("0"); ?>'/>
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
		var res2 = res.toFixed(2); 
		var add = $("#price-"+x).html()*y;
		var p1 = $(this).parent().attr("id");
		var p2 = $("#"+p1).parent().find(".price-all").html(res2);
		
		var totalcount = 0;
		var totalprice = 0;
		
		$(".quantityTb").each(function() {
			var v = $(this).attr("value");
			totalcount += parseInt(v);
		});
		
		$(".price-all").each(function() {
			var v = $(this).html();
			totalprice += parseInt(v);
		});
		
		var spr = 1;
		
		if(spr = <?php echo($ex); ?>) {
			if(totalcount>= <?php echo($ct); ?>)
			{
				 totalprice=totalprice-(totalprice*<?php echo($dc); ?>/100);
				  $("#disc").html('(-<?php echo($dc); ?>%)');
				  $("#discounthid").attr('value', '<?php echo($dc);?>');			 
			} else {
				$("#disc").html('');
				$("#discounthid").attr('value', '0');			 
			}
		}
		
		
		totalprice = totalprice.toFixed(2); 
		// var cp = $("#complPrice").html() - add + res;
		// var cp = cp.toFixed(2); 
		$("#complPrice").html(totalprice);
	});

	$( ".quantityTb" ).focus(function() {
		x = $(this).attr("id");
		y = $(this).attr("value");
		x = x.substring(8);
	});
	
});



$('.column-remove').click(function() {
	if (jQuery.cookie("cartItem")) {
		
		var cookieval = $.cookie("cartItem");
		var minuscookie = $(this).attr("id");
		var newcookie = cookieval.replace(minuscookie,'');
		
	}
	else{
	}
	$.cookie("cartItem", newcookie, { expires: 0, path: '/' });
	
	if (newcookie.length > 0) {
		$.cookie("cartItem", newcookie, { expires: 1, path: '/' });
	}
	//)
	//checkCart();
	
});
</script>