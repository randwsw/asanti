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
	<div class="cart-fill">
		<div class="product-row" id="top-row">
			<div class="column-name">
				<p>Nazwa produktu</p>
			</div>
			<div class="column-size">
				<p>Rozmiary</p>
			</div>
			<div class="column-color">
				<p>Kolor</p>
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
			public $color = "";
			function addSize($sizeItem)
			{
			    array_push($this->sizes, $sizeItem);
			}		
		}
		
		$cartItems = array();
		$querypart = "";
		
		
		$sum=0;
		$check = 0; 
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
						// echo("TEXT TO GET: ".$text_to_get);
						// echo("<br>");
						 $cookieval = substr($cookieval, $end_pos);
						 // echo($cookieval."<br>");
						 $cookievalArray = str_split($cookieval);
					}
					if (array_search(']', $cookievalArray)==null) {
					    $i++;
					}
				}
				
				 $end_pos =  array_search(')', $cookievalArray)-1; 
				 $citem->color = substr($cookieval, 1, $end_pos);
				 $cookieval = substr($cookieval, $end_pos+5);
				$citem->count = $cookieval;
				array_push($cartItems, $citem);
				}
// 				
			
			// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
			require_once("include/config.php");
	   		$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
			mysqli_set_charset($conn, "utf8");
			// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
					
			// Check connection
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
		  	
		  	 $count = 0;
			 // $globalcount = 0;
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
				// $globalcount += $val->count;
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
					<div class='column-color'>
						<p>".$val->color."</p>
						<input type='hidden' value='".$val->color."' name='color[]'>
					</div>
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

		  $sql= mysqli_query($conn, "SELECT value, items_value FROM discount WHERE active = 1 ORDER BY items_value ASC;");

		  // $ex = 0;
		  // $ct = 0;
		  // $dc= 0;
		  $out = array();
		  $check = 0;
		while($rec = mysqli_fetch_object($sql)) {
			// $dc = $rec['value'];
			// $ct = $rec['items_value'];
			// $ex = 1;
			$out[] =$rec;
			$check = 1;
		}
		// print_r($out);
		$sum = number_format($sum, 2, '.','');
		$stemp = number_format($sum, 2, '.','');
		$dval=0;
		foreach ($out as $object) {
		    if($sum > $object->items_value)
		    {
		    	$stemp = 0;
		    	$stemp = $sum*(1-(($object->value)/100));
				$dval = $object->value;
		    } 
		}
		if($check == 1){
			$sum = $stemp;
			$sum = number_format($sum, 2, '.','');
		}
		// if($ex==1){
			// if($globalcount>=$ct) {
				// $sum=$sum-($sum*$dc/100);
				// $sum = number_format($sum, 2, '.', '');
			// } 
		// }				
		
		mysqli_close($conn);	
        }
		else
			{
				echo("<div id='emptyCart'><p>Nie dodano jeszcze żadnego produktu</p></div>");
			}
			
		
		?>
		<div class="product-row" id="bot-row">
			<!-- <div class="column-submit">
				<p><a href="index.php">wróć do sklepu</a></a></p>
			</div> -->
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
						<?php if($check==1) { if($dval!=0){ echo("(-".$dval."%)");} else {echo("");}}
						?>
					</p>
			</div>
			<div class="column-submit">
				<p></p>
			</div>
		</div>
		<input id='discounthid' type='hidden' name='dischid' value='<?php if($check==1) { if($dval!=0){ echo($dval);} else {echo("0");}} ?>'/>
		<div class="product-row" id="bot-row">
			<div class="column-fill">
				<p>Rodzaj przesyłki:</p>
			</div>
			<div class="column-name">
				<?php
					// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
					$conn2=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
					mysqli_set_charset($conn2, "utf8");
					// //////////////////////////////////////////////////////////////////////////////////////////////////// //	
							
					// Check connection
					if (mysqli_connect_errno())
					{
						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
						echo("
						<div class='styled-select-shipping'>
							<label>
							<select class='ship-sel'>");

							$sql2 = mysqli_query($conn2, "SELECT name, value FROM shipping WHERE active = 1 ORDER BY value");
							

							$firstval = 0;
							$firstnameval = "";
							$i = 0;
							while($row2= mysqli_fetch_array($sql2))
							{
								if($i==0) {
									$firstval = $row2['value'];
									$firstnameval = $row2['name'];
									$i=1;
								}
								$value = $row2['value'];
								$name = $row2['name'];
								echo("<option value='$value'>$name - $value zł</option>");
								
							}
							echo("<label>
							</select>
						</div>");
						mysqli_close($conn2);	
					
				?>
			</div>
			<div class="column-name">
				<p>Razem z przesyłką:</p>
			</div>
			<div class="column-price-all">
				<p id="pricefinal">
					
					<?php 
						$nsum = number_format($sum+$firstval, 2, '.','');
						echo($nsum); 
					?>
				</p>
				<input id='shippinghid' type='hidden' name='shippinghid' value='<?php echo($firstval); ?>'/>
				<input id='shippnamehid' type='hidden' name='shippnamehid' value='<?php echo($firstnameval); ?>'/>
			</div>
			<div class="column-submit">
				<p><a id='cartSubmit' onclick="document.cartForm.submit();">Kup</a></a></p>
			</div>
		</div>
		</form>
		<div class="product-row" id="bot-row" style="border: 0px">
			<div class='column-price-all' style="height: inherit">
					<p style="margin-top: 10px;"><a href='index.php'>wróć do sklepu</a></p>
			</div>
			<div class='column-price-all'  style="height: inherit">
					<p style="margin-top: 10px;"><a onclick="history.back(-1)">wróć</a></p>
			</div>
		</div>
		</div>
	</div>
	 <?php include 'include/footer.php'; ?> 

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
		
		var items_value = [];
		var value = [];
		var count = 0;
		<?php 
		foreach ($out as $object) {
			?>
				items_value.push(<?php echo($object->items_value);?>);
				value.push(<?php echo($object->value);?>);
				count++;
			
		<?php
		}
		?>
		
		var tempprice = 0;
		var v = '';
		for(var i=0;i<count;i++){
		
			if(items_value[i]<totalprice) {
					tempprice = 0;
					tempprice = totalprice*(1-(value[i]/100));
					v =value[i];
			}
		}
		
		if(v!='') {
			totalprice = tempprice;
			$("#disc").html("(-"+v+"%)");
		} else {
			$("#disc").html("");
			v = 0;
		}
		
		
		var shipping =$('.ship-sel').find(":selected").attr("value");
		 
		var shipint = parseInt(shipping);
		var pricefinal = totalprice+shipint;
		totalprice = totalprice.toFixed(2); 
		pricefinal = pricefinal.toFixed(2); 
		
		$("#discounthid").attr('value', v);			 
		$("#complPrice").html(totalprice);
		
		
		$("#pricefinal").html(pricefinal);
	});

	$( ".quantityTb" ).focus(function() {
		x = $(this).attr("id");
		y = $(this).attr("value");
		x = x.substring(8);
	});
	
	$( ".ship-sel" ).change(function() {
		var shipping =$('.ship-sel').find(":selected").attr("value");
		
		$("#shippinghid").attr("value",shipping);
		var shipfloat = parseFloat(shipping);
		
		var cprice = $("#complPrice").html();
		var cpricefloat = parseFloat(cprice);
		
		var pricefinal = shipfloat+cpricefloat;
		pricefinal = pricefinal.toFixed(2); 
		$("#pricefinal").html(pricefinal);
		
		var shippname = $('.ship-sel').find(":selected").html();

		shippname = shippname.substr(0, shippname.indexOf('-'));
		$("#shippnamehid").attr("value",shippname);
		
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