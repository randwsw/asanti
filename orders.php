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
		$result = mysqli_query($conn,"SELECT o.id AS oid, order_date, status, order_value, disc  FROM orders o, users u WHERE u.id = (SELECT id FROM users WHERE email = '$login') AND u.id = o.user_id;");
		while($e = mysqli_fetch_array($result))
		  {
		  	switch ($e['status']) {
			    case 0:
			        $status='Niezapłacone';
			        break;
			    case 1:
			        $status='Zapłacone';
			        break;
			    case 2:
			        $status='Zakończone';
			        break;
			}
			$ov = $e['order_value']*(1-($e['disc']/100));
			$ov = number_format($ov, 2, '.', '');
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
						<p>".$ov."</p>
					</div>
				</div>"
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
		$result = mysqli_query($conn,"SELECT user_id, disc FROM orders WHERE id = $oid AND user_id = (SELECT id FROM users WHERE email = '$login') GROUP BY user_id;");
		while($e = mysqli_fetch_array($result))
		  {
				$user_id = $e['user_id'];
				$disc = $e['disc'];
		  }
		if($user_id == null) {
			header('Location: index.php');
		}
		$sum=0;
		$result = mysqli_query($conn,"SELECT i.id AS iid, i.name, quantity, oc.price AS priceoc, oc.sizes FROM orders_con oc, item i WHERE item_id = i.id AND order_id=$oid;");
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
			<div class='column-price'>
				<p><a href='orders.php'>wróć do zamówień</a></a></p>
			</div>
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
	}
}
?>

</div>
</body>
</html>