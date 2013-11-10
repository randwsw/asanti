<?php
if(isset($_POST['name'])) {
	$name = $_POST['name'];
} else {
	header('Location: ../cart.php');
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
		header('Location: ../login.php?cr=1');
	}else {
		// header('Location: ../orders.php');
		// setcookie("cartItem", "", time()-3600, '/');
		require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		
		
		
		/*---------*/
		
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
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

		
mysqli_query($conn,"INSERT INTO orders (user_id, order_date, status, order_value, disc, shipping_value, shipping_name)
		VALUES ('$uid', '$date', 0, $sum, $disc, $shipp, '$shippnamehid')");
$oid= mysqli_insert_id($conn);
		
foreach( $name as $key => $n ) {		
		if (!mysqli_query($conn, "INSERT INTO orders_con (order_id, item_id, quantity, price, sizes, color)
		VALUES ('$oid', $iid[$key] ,$quantity[$key] , $price[$key], '$sizes[$key]', '$color[$key]')")) {
    		printf("Errormessage: %s\n", mysqli_error($conn));
			
		}
}

/*PAYU*/
		include_once("sdk/openpayu.php");
		include_once("config.php");
		
		$directory = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
		$myUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] .$directory;
		
		$_SESSION['sessionId'] = md5(rand() . rand() . rand() . rand());
		
		$shippingCost = array(
		    'CountryCode' => 'PL',
		    'ShipToOtherCountry' => 'true',
		    'ShippingCostList' => array(
		        array(
		            'ShippingCost' => array(
		                'Type' => $shippnamehid,
		                'CountryCode' => 'PL',
		                'Price' => array(
		                    'Gross' => ($shipp*100), 'Net' => '0', 'Tax' => '0', 'TaxRate' => '0', 'CurrencyCode' => 'PLN'
		                )
		            )
		        )
			)			
		);


$result = mysqli_query($conn,"SELECT i.id AS iid, i.name, quantity, color, oc.price AS priceoc, oc.sizes FROM orders_con oc, item i WHERE item_id = i.id AND order_id=$oid;");
/*PAYU - ITEMS*/
$itemarray = array();
while($e = mysqli_fetch_array($result))
		  {
		  	$item = array(
			    'Quantity' => $e['quantity'],
			    'Product' => array (
			        'Name' => $e['name'],
			        'UnitPrice' => array (
			            'Gross' => ($e['priceoc']*100), 'Net' => 0, 'Tax' => 0, 'TaxRate' => '0', 'CurrencyCode' => 'PLN'
			        )
			    )
			);
			array_push($itemarray, $item);
		  }

$ov = $sum*(1-($disc/100));

$shoppingCart = array(
    'GrandTotal' => ($ov*100),
    'CurrencyCode' => 'PLN',
    'ShoppingCartItems' => array ()
);

foreach ($itemarray as $value) {
   array_push(
   	$shoppingCart['ShoppingCartItems'],
   	array ('ShoppingCartItem' => $value)
   );
}

// Order structure
$order = array (
    'MerchantPosId' => OpenPayU_Configuration::getMerchantPosId(),
    'SessionId' => $_SESSION['sessionId'],
    'OrderUrl' => $myUrl . '/layout/page_cancel.php?order=' . rand(), // is url where customer will see in myaccount, and will be able to use to back to shop.
    'OrderCreateDate' => date("c"),
    'OrderDescription' => 'Oplata za zamowienie nr: '.$oid ,
    'MerchantAuthorizationKey' => OpenPayU_Configuration::getPosAuthKey(),
    'OrderType' => 'MATERIAL', // options: MATERIAL or VIRTUAL
    'ShoppingCart' => $shoppingCart
);

// OrderCreateRequest structure
$OCReq = array (
    'ReqId' =>  md5(rand()),
    'CustomerIp' => '127.0.0.1', // note, this should be real ip of customer retrieved from $_SERVER['REMOTE_ADDR']
    'NotifyUrl' => $myUrl . '/OrderNotifyRequest.php', // url where payu service will send notification with order processing status changes
    'OrderCancelUrl' => $myUrl . '/layout/page_cancel.php',
    'OrderCompleteUrl' => $myUrl . '/layout/page_success.php',
    'Order' => $order,
    'ShippingCost' => array(
        'AvailableShippingCost' => $shippingCost,
        'ShippingCostsUpdateUrl' => $myUrl . '/ShippingCostRetrieveRequest.php' // this is url where payu checkout service will send shipping costs retrieve request
    )
);

$login = $conn->real_escape_string($_SESSION['login']);
$login = $purifier->purify($login);

$result = mysqli_query($conn,"SELECT pValue, email, password, name, lastname, pcode, street, city, hnum, anum FROM users u, address a, phone p WHERE email = '$login' AND p.user_id = u.id AND a.user_id = u.id");
				while($e = mysqli_fetch_array($result))
				  {
						$phone = $e['pValue'];
						$name = $e['name'];
						$lastname = $e['lastname'];
						$pcode = $e['pcode'];
						$street = $e['street'];
						$city = $e['city'];
						$password = $e['password'];
						$hnum = $e['hnum'];
						$anum = $e['anum'];
				  }
		// echo($name." ".$lastname);
	mysqli_close($conn);
	if($anum==0) {
		$anum='';
	}
# if logged customer in eshop
$customer = array(
    'Email' => $login,
    'FirstName' => $name,
    'LastName' => $lastname,
    'Phone' => $phone,
    'Language' => 'pl_PL',
    /* Shipping address*/
    'Shipping' => array(
        'Street' => $street,
        'HouseNumber' => $hnum,
        'ApartmentNumber' => $anum,
        'PostalCode' => $pcode,
        'City' => $city,
        'CountryCode' => 'PL',
        'AddressType' => 'SHIPPING',
        'RecipientName' => $name." ".$lastname
    ),
    /* Invoice billing data */
    'Invoice' => array(
        'Street' => $street,
        'HouseNumber' => $hnum,
        'ApartmentNumber' => $anum,
        'PostalCode' => $pcode,
        'City' => $city,
        'CountryCode' => 'PL',
        'AddressType' => 'BILLING',
        'RecipientName' => 'PayU SA',
        'TIN' => ''
    )
);


if(!empty($customer))
    $OCReq['Customer'] = $customer;


// send message OrderCreateRequest, $result->response = OrderCreateResponse message
$result = OpenPayU_Order::create($OCReq);

}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order Create Example</title>
    <link rel="stylesheet" href="layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="layout/css/style.css">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Order create Example</h1>
    </div>
<?php
echo($_SERVER['REMOTE_ADDR']);
if ($result->getSuccess()) {
    $result = OpenPayU_OAuth::accessTokenByClientCredentials();
?>
<p>Zapłać</p>
<form method="GET" action="<?php echo OpenPayu_Configuration::getSummaryUrl();?>">
    <fieldset>
        <input type="hidden" name="sessionId" value="<?php echo $_SESSION['sessionId'];?>">
        <input type="hidden" name="oauth_token" value="<?php echo $result->getAccessToken();?>">
        <p>
            <input type="hidden"  name="showLoginDialog" id="showLoginDialogSelect" value="False">
            <input type="submit" class="btn btn-primary" value="Next step (summary page) >">
        </p>
    </fieldset>
</form>
<?php
} else {
    echo '<h4>Debug console</h4><pre>';
    OpenPayU_Order::printOutputConsole();
    echo '<br/><strong>ERROR:</strong><br />' . $result->getError() . ' ' . $result->getMessage() . '</pre>';
}
?>
</div>
</body>
</html>