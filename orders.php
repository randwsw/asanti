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

<?php

if(!session_id())
	{
	    session_start();
	}
	if(!isset($_SESSION['login'])) {
		header('Location: shop.php');
	}else {
		
		require_once 'htmlpurifier/library/HTMLPurifier.auto.php';

		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		
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
		<img src='img/forkids2.png' id='log-logo'>
		<br/>
		<h2>Zamówienia</h2>
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
		$result = mysqli_query($conn,"SELECT o.id AS oid, order_date, status, order_value FROM orders o, users u WHERE u.id = (SELECT id FROM users WHERE email = '$login') AND u.id = o.user_id;");
		while($e = mysqli_fetch_array($result))
		  {
				echo(
  				"<div class='product-row' id='middle-row-o'>
					<div class='column-name'>
						<p><a href='orders.php?id=".$e['oid']."'>".$e['oid']."</a></p>
					</div>
					<div class='column-price'>
						<p>".$e['order_date']."</p>
					</div>
					<div class='column-quantity'>
						<p>".$e['status']."</p>
					</div>
					<div class='column-price-all'>
						<p>".$e['order_value']."</p>
					</div>
				</div>"
	);
		  }
}else if(isset($_GET['id'])) {
		$oid = $_GET['id'];
		?>
		<br/>
		<img src='img/forkids2.png' id='log-logo'>
		<br/>
		<h2>Zamówienie numer:<?php echo(" ".$oid); ?></h2>
		<div class="product-row" id="top-row-os">
			<div class="column-name">
				<p>Nazwa</p>
			</div>
			<div class='column-size'>
				<p>Rozmiary</p>
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
		$result = mysqli_query($conn,"SELECT user_id FROM orders WHERE user_id = (SELECT id FROM users WHERE email = '$login') GROUP BY user_id;");
		while($e = mysqli_fetch_array($result))
		  {
				$user_id = $e['user_id'];
		  }
		if($user_id == null) {
			header('Location: shop.php');
		}
		$sum=0;
		$result = mysqli_query($conn,"SELECT i.name, quantity, oc.price AS priceoc, oc.sizes FROM orders_con oc, item i WHERE item_id = i.id AND order_id=$oid;");
		while($e = mysqli_fetch_array($result))
		  {
		  	$sum=$sum+$e['priceoc'];
				echo("
				<div class='product-row' id='middle-row-os'>
					<div class='column-name'>
						<p>".$e['name']."</p>
					</div>
					<div class='column-size'>
						<p>".$e['sizes']."</p>
					</div>
					<div class='column-price'>
						<p>".$e['priceoc']."</p>
					</div>
					<div class='column-quantity'>
						<p>".$e['quantity']."</p>
					</div>
					<div class='column-price-all'>
						<p>".$e['quantity']*$e['priceoc']."</p>
					</div>
				</div>");
		  }
		 echo("
		  <div class='product-row' id='bot-row-os'>
			<div class='column-price'>
				<p><a href='orders.php'>wróć do zamówień</a></a></p>
			</div>
			<div class='column-quantity'>
				<p>Razem:</p>
			</div>
			<div class='column-price-all'>
				<p id='complPrice'>".$sum."</p>
			</div>
		</div>");
	}
}
?>

</div>
</body>
</html>