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
<div class ="container">

<?php

if(!session_id())
	{
	    session_start();
	}
	if(!isset($_SESSION['login'])) {
		header('Location: index.php');
	}else {
		
		require_once 'htmlpurifier/library/HTMLPurifier.auto.php';

		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		
		require_once("include/config.php");
	   	$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
		mysqli_set_charset($conn, "utf8");
		
		$login = $conn->real_escape_string($_SESSION['login']);
		$login= $purifier->purify($login);
		
		$returnValue = null;
		
		if (mysqli_connect_errno())
			{
		 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		if(!isset($_GET['id'])) { 
		?>
		<br/>
		<a href='index.php'><img src='img/nextlogo.png' id='log-logo'></a>
		<br/>
		<h2>Zamówienia</h2>
		<div class="oinfo">
			Zmiana statusu zamówienia (zaksięgowanie należności) może potrwać kilka chwil. Aby upewnić się, że otrzymaliśmy Twoją wpłatę, odwiedź tę stronę ponownie za parę minut. W przypadku problemów prosimy o kontakt na <a>pomoc@asanti.com</a>.
		</div>
		<div class="product-row" id="top-row-o">
			<div class="column-name">
				<p>Numer zamówienia</p>
			</div>
			<div class="column-price">
				<p>Data</p>
			</div>
			<div class="column-quantity">
				<p>Status</p>
			</div>
			<div class="column-price-all">
				<p>Cena całkowita</p>
			</div>
		</div>
		<?php
		$result = mysqli_query($conn,"SELECT o.id AS oid, order_date, status, order_value, disc, shipping_value FROM orders o, users u WHERE u.id = (SELECT id FROM users WHERE email = '$login') AND u.id = o.user_id;");
		while($e = mysqli_fetch_array($result))
		  {
		  	switch ($e['status']) {
			    case 0:
			        $status='Niezapłacone';
			        break;
			    case 1:
			        $status='Realizowane';
			        break;
			    case 2:
			        $status='Zapłacone';
			        break;
				case 3:
			        $status='Anulowane';
			        break;
			}
			$sv = $e['shipping_value'];
			$sv = number_format($sv, 2, '.', '');
			$ov = $e['order_value']*(1-($e['disc']/100));
			$ov = number_format($ov, 2, '.', '');
			$ovsv = $ov+$sv;
			$ovsv = number_format($ovsv, 2, '.', '');
				echo(
  				"<div class='product-row' id='middle-row-o'>
					<div class='column-name'>
						<p><a href='orders.php?id=".$e['oid']."'>".$e['oid']."</a></p>
					</div>
					<div class='column-price'>
						<p>".$e['order_date']."</p>
					</div>
					<div class='column-quantity'>
						<p>".$status."</p>
					</div>
					<div class='column-price-all'>
						<p>".$ovsv."</p>
					</div>");
					if($e['status']==0) {
						echo(
							"<div class='column-pay'>
								<p id=o_".$e['oid']."><a>Zapłać</a></p>
							</div>
							<div class='column-cancel'>
								<!--<p><a href='payu/OrderCancelRequest.php?id=".$e['oid']."'>Anuluj</a></p>-->
							</div>
					");
					}
				echo("</div>"
		);
		  }
		 	 echo(
  				"<div class='product-row' id='bot-row-o'>
					<div class='column-name'>
						<p></p>
					</div>
					<div class='column-price'>
						<p></p>
					</div>
					<div class='column-quantity'>
						<p><a href='profile.php'>wróć do profilu</a></p>
					</div>
					<div class='column-price-all'>
						<p><a href='index.php'>wróć do sklepu</a></p>
					</div>
				</div>");
}else if(isset($_GET['id'])) {
		$oid = $_GET['id'];
		?>
		<br/>
		<a href='index.php'><img src='img/nextlogo.png' id='log-logo'></a>
		<br/>
		<h2>Zamówienie numer:<?php echo(" ".$oid); ?></h2>
		<div class="product-row" id="top-row-os">
			<div class="column-name">
				<p>Nazwa</p>
			</div>
			<div class='column-size'>
				<p>Rozmiary</p>
			</div>
			<div class='column-color'>
				<p>Kolor</p>
			</div>
			<div class="column-price">
				<p>Cena</p>
			</div>
			<div class="column-quantity">
				<p>Ilość</p>
			</div>
			<div class="column-price-all">
				<p>Cena całkowita</p>
			</div>
		</div>
		<?php
		$user_id = null;
		$result = mysqli_query($conn,"SELECT user_id, disc, shipping_value, shipping_name FROM orders WHERE id = $oid AND user_id = (SELECT id FROM users WHERE email = '$login') GROUP BY user_id;");
		while($e = mysqli_fetch_array($result))
		  {
				$user_id = $e['user_id'];
				$disc = $e['disc'];
				$sv = $e['shipping_value'];
				$sn = $e['shipping_name'];
		  }
		if($user_id == null) {
			header('Location: index.php');
		}
		$sum=0;
		$result = mysqli_query($conn,"SELECT i.id AS iid, i.name, quantity, color, oc.price AS priceoc, oc.sizes FROM orders_con oc, item i WHERE item_id = i.id AND order_id=$oid;");
		while($e = mysqli_fetch_array($result))
		  {
		  	$sum=$sum+($e['priceoc']*$e['quantity']);
				echo("
				<div class='product-row' id='middle-row-os'>
					<div class='column-name'>
						<p><a href='item.php?id=".$e['iid']."'>".$e['name']."</a></p>
					</div>
					<div class='column-size'>
						<p>".$e['sizes']."</p>
					</div>
					<div class='column-color'>
						<p>".$e['color']."</p>
					</div>
					<div class='column-price'>
						<p>".$e['priceoc']."</p>
					</div>
					<div class='column-quantity'>
						<p>".$e['quantity']."</p>
					</div>
					<div class='column-price-all'>
						<p>".$prc = number_format($e['quantity']*$e['priceoc'], 2, '.', '')."</p>
					</div>
				</div>");
		  }
		  
		$sum = $sum*(1-($disc/100));
		$sum = number_format($sum, 2, '.', '');
		 echo("
		  <div class='product-row' id='bot-row-os'>
			<div class='column-quantity'>
				<p>Razem:</p>
			</div>
			<div class='column-price-all'>
				<p id='complPrice'>".$sum."</p>");
				if($disc>0) {
					echo("<p id='disc'>(-".$disc."%)</p>");
				}
			echo("</div>
		</div>");
		echo("
		  <div class='product-row' id='bot-row-oss'>
		  	<div class='column-price-all'>
				<p><a href='orders.php'>wróć do zamówień</a></a></p>
			</div>
			<div class='column-quantity'>
				<p>Przesyłka:</p>
			</div>
			<div class='column-shipping'>
				<p id='pshipping'>".$sn." - ".$sv." zł</p>
			</div>
			<div class='column-quantity'>
				<p>Razem:</p>
			</div>
			<div class='column-price-all'>
				<p id='complPrice'>".(number_format($sum+$sv, 2, '.', ''))."</p>
			</div>	
		</div>");
	}
}
mysqli_close($conn);
?>

</div>
<div class='cartInfoDiv'>
		<div id='info'>
			Aby przejść do systemu płatności elektronicznej, wciśnij przycisk "płacę z payu".
		</div>
		<div id="buttons">
			<div id="left">wróć</div>
			<div id="right">płacę z payu</div>
		</div>
	</div>
	
	<div class="cartbg"></div>
	<div class="cartInfoZ"><img src="img/ajax-loader.gif"></div>
</body>
</html>
<script type="text/javascript">
$('.column-pay p').click(function(){
         $(".cartInfoDiv").css("display", "block");
         $(".cartbg").css("display", "block");
         var id = $(this).attr("id");
         id = id.substring(2);        
         $('#buttons #right').attr("data", id);
});
$('#buttons #left, .cartbg').click(function(){
         $(".cartInfoDiv").css("display", "none");
         $(".cartbg").css("display", "none");
});
$('#buttons #right').click(function(){
	var id = $(this).attr("data");
	$(".cartInfoZ").css("display","block"); 
        $.ajax({
           type: "POST",
           url: 'payu/repay.php',
           data: {id: id},
           success: function(data)
           {
              window.location.href=data;
           }
         });
});
</script>
