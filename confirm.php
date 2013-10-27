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

if(!session_id())
	{
	    session_start();
	}
	if(!isset($_SESSION['login'])) {
		header('Location: login.php?cr=1');
	}else {
		setcookie("cartItem", "", time()-3600, '/');
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
			
		foreach( $name as $key => $n ) {
		  $sum = $sum + ($price[$key]*$quantity[$key]);
		} 

$result = mysqli_query($conn,"SELECT u.id FROM users u WHERE email = '$login'");
				while($e = mysqli_fetch_array($result))
				  {
						$uid = $e['id'];
				  }
		$date = date('d-m-Y H:i:s');
		
mysqli_query($conn,"INSERT INTO orders (user_id, order_date, status, order_value, disc)
		VALUES ('$uid', '$date', 0, $sum, $disc)");
$oid= mysqli_insert_id($conn);
		
foreach( $name as $key => $n ) {		
		if (!mysqli_query($conn, "INSERT INTO orders_con (order_id, item_id, quantity, price, sizes)
		VALUES ('$oid', $iid[$key] ,$quantity[$key] , $price[$key], '$sizes[$key]')")) {
    		printf("Errormessage: %s\n", mysqli_error($conn));
		}
}

header('Location: orders.php?id='.$oid.'');
}
?>