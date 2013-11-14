<?php
if(isset($_POST['name'])) {
	$name = $_POST['name'];
} else {
	header('Location: cart.php');
}
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$iid = $_POST['iid'];
$sizes = $_POST['sizes'];
$sum=0;
$disc = $_POST['dischid'];
$color = $_POST['color'];
$shipp = $_POST['shippinghid'];
$shippnamehid = $_POST['shippnamehid'];

if(!session_id())
	{
	    session_start();
	}
	if(!isset($_SESSION['login'])) {
		header('Location: login.php?cr=1');
	}else {
		header('Location: orders.php');
		setcookie("cartItem", "", time()-3600, '/');
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
			
		foreach( $name as $key => $n ) {
		  $sum = $sum + ($price[$key]*$quantity[$key]);
		} 

$result = mysqli_query($conn,"SELECT u.id FROM users u WHERE email = '$login'");
				while($e = mysqli_fetch_array($result))
				  {
						$uid = $e['id'];
				  }
		$date = date('d-m-Y H:i:s');
echo("INSERT INTO orders (user_id, order_date, status, order_value, disc, shipping_value, shipping_name)
		VALUES ('$uid', '$date', 0, $sum, $disc, $shipp, $shippnamehid)");
		
mysqli_query($conn,"INSERT INTO orders (user_id, order_date, status, order_value, disc, shipping_value, shipping_name)
		VALUES ('$uid', '$date', 0, $sum, $disc, $shipp, '$shippnamehid')");
$oid= mysqli_insert_id($conn);
		
foreach( $name as $key => $n ) {		
		if (!mysqli_query($conn, "INSERT INTO orders_con (order_id, item_id, quantity, price, sizes, color)
		VALUES ('$oid', $iid[$key] ,$quantity[$key] , $price[$key], '$sizes[$key]', '$color[$key]')")) {
    		printf("Errormessage: %s\n", mysqli_error($conn));
		}
}


}
?>