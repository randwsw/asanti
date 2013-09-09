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
	<br/>
	<img src='img/forkids2.png' id='log-logo'>
	<br/>
	<h2>Zamówienie</h2>
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
		</div>
<?php
if(isset($_POST['name'])) {
	$name = $_POST['name'];
} else {
	header('Location: cart.php');
}
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$sum=0; 

foreach( $name as $key => $n ) {
  $sum = $sum + ($price[$key]*$quantity[$key]);
  echo(
  				"<div class='product-row' id='middle-row'>
					<div class='column-name'>
						<p>".$n."</p>
					</div>
					<div class='column-price'>
						<p>".$price[$key]."</p>
					</div>
					<div class='column-quantity'>
						<p>".$quantity[$key]."</p>
					</div>
					<div class='column-price-all'>
						<p>".$price[$key]*$quantity[$key]."</p>
					</div>
				</div>"
	);
}


?>
<div class="product-row" id="bot-row">
			<div class="column-name">
				<p>Razem do zapłaty:</p>
			</div>
			<div class="column-price-all">
				<p ><?php echo($sum); ?> zł</p>
			</div>
			<div class="column-remove">
			</div>
		</div>

<br/> 
<br/>
<br/>
<div id='phead'><h2>Dane do wysyłki </h2><a href="profile.php">( profil użytkownika )</a></div>	
<div class="product-row" id="top-row">
			<div class="column-name1">
				<p>Imię</p>
			</div>
			<div class="column-name2">
				<p>Nazwisko</p>
			</div>
			<div class="column-address">
				<p>Adres</p>
			</div>
			<div class="column-phone">
				<p>Telefon</p>
			</div>
		</div>
<?php
if(!session_id())
	{
	    session_start();
	}
	if(!isset($_SESSION['login'])) {
		header('Location: login.php');
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
		
		
		
		$result = mysqli_query($conn,"SELECT pValue, email, name, lastname, pcode, street, city FROM users u, address a, phone p WHERE email = '$login' AND p.user_id = u.id AND a.user_id = u.id");
				while($e = mysqli_fetch_array($result))
				  {
						$phone = $e['pValue'];
						$name= $e['name'];
						$lastname = $e['lastname'];
						$pcode = $e['pcode'];
						$street = $e['street'];
						$city = $e['city'];
				  }
		mysqli_close($conn);
	} 
echo("
<div class='product-row' id='middle-row'>
			<div class='column-name1'>
				<p>$name</p>
			</div>
			<div class='column-name2'>
				<p>$lastname</p>
			</div>
			<div class='column-address'>
				<p>$street,  $pcode $city</p>
			</div>
			<div class='column-phone'>
				<p>$phone</p>
			</div>
		</div>
");
?>
<div class="product-row" id="bot-row">
	<div class="column-remove">
		<p><a href="cart.php">wróć do koszyka</a></p>
	</div>
	<div class="column-remove">
		<p><a href="shop.php">wróć do sklepu</a></p>
	</div>
	<div class="column-remove">
		<p><a href="">Zapłać</a></p>
	</div>
</div>
</div>
</body>
</html>