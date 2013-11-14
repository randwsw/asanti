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

		

		$sessionstring = $uid;
		// while(strlen($sessionstring)<7) {
			// $sessionstring="0".$sessionstring;
		// }
		$sessionstring=$sessionstring."U";
		
		$result = mysqli_query($conn,"SELECT count(*) as count FROM orders WHERE user_id=$uid");
				while($e = mysqli_fetch_array($result))
				  {
						$count = $e['count'];
				  }
		echo($count."<br>");
		$orderidstring = ($count+1);
		// while(strlen($orderidstring)<7) {
			// $orderidstring="0".$orderidstring;
		// }
		$sessionstring.=$orderidstring;
		$sessionstring.="U";
		// $sessionstring.= date("Ymd"); //:Y:m:d	
		$p1 = hash_hmac('adler32',  date("H:i:s"), 'asanti');
		$p2 = hash_hmac('adler32',  date("Ymd"), 'asanti');
		$sessionstring.=$p1.$p2;
		// $activationKey = md5(time().date("Y:m:d"));
		echo($sessionstring."<br>");
/*PAYU*/
		include_once("sdk/openpayu.php");
		include_once("config.php");
		
		$directory = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
		$myUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] .$directory;
		
		$_SESSION['sessionId'] = $sessionstring;
		
		// $shippingCost = array(
		    // 'CountryCode' => 'PL',
		    // 'ShipToOtherCountry' => 'true',
		    // 'ShippingCostList' => array(
		        // array(
		            // 'ShippingCost' => array(
		                // 'Type' => $shippnamehid,
		                // 'CountryCode' => 'PL',
		                // 'Price' => array(
		                    // 'Gross' => ($shipp*100), 'Net' => '0', 'Tax' => '0', 'TaxRate' => '0', 'CurrencyCode' => 'PLN'
		                // )
		            // )
		        // )
			// )			
		// );
		
		$shippingCost = array(
		    'CountryCode' => 'PL',
		    'ShipToOtherCountry' => 'true',
		    'ShippingCostList' => array()			
		);
		
		$result = mysqli_query($conn,"SELECT * FROM shipping;");
		$shipprray = array();
		while($e = mysqli_fetch_array($result)) {
			$shipprray =  array(
		            'ShippingCost' => array(
		                'Type' => $e['name'],
		                'CountryCode' => 'PL',
		                'Price' => array(
		                    'Gross' => ($e['value']*100), 'Net' => '0', 'Tax' => '0', 'TaxRate' => '0', 'CurrencyCode' => 'PLN'
		                )
		            )
		        );
			array_push($shippingCost['ShippingCostList'],$shipprray);
		}

/*PAYU - ITEMS*/
$itemarray = array();
// while($e = mysqli_fetch_array($result))
		  // {
		  	// $item = array(
			    // 'Quantity' => $e['quantity'],
			    // 'Product' => array (
			        // 'Name' => $e['name'].". Kolor: ".$e['color'],
			        // 'UnitPrice' => array (
			            // 'Gross' => ($e['priceoc']*100), 'Net' => 0, 'Tax' => 0, 'TaxRate' => '0', 'CurrencyCode' => 'PLN'
			        // )
			    // )
			// );
			// array_push($itemarray, $item);
// }


foreach( $name as $key => $n ) {
	$tempid = $iid[$key];
	$result = mysqli_query($conn,"SELECT name FROM item WHERE id=$tempid;");	
	while($e = mysqli_fetch_array($result)) {
		$name = $e['name'];
	}	
	$item = array(
			    'Quantity' => $quantity[$key],
			    'Product' => array (
			        'Name' => $name.". Kolor: ".$color[$key],
			        'UnitPrice' => array (
			            'Gross' => ($price[$key]*100), 'Net' => 0, 'Tax' => 0, 'TaxRate' => '0', 'CurrencyCode' => 'PLN'
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
    'OrderUrl' => 'http://www.wildeast.pl/orders.php', // is url where customer will see in myaccount, and will be able to use to back to shop.
    'OrderCreateDate' => date("c"),
    'OrderDescription' => 'Oplata za zamowienie nr: ' ,
    'MerchantAuthorizationKey' => OpenPayU_Configuration::getPosAuthKey(),
    'OrderType' => 'MATERIAL', // options: MATERIAL or VIRTUAL
    'ShoppingCart' => $shoppingCart
);

// OrderCreateRequest structure
$OCReq = array (
    'ReqId' =>  md5(rand()),
    'CustomerIp' => $_SERVER['REMOTE_ADDR'], // note, this should be real ip of customer retrieved from $_SERVER['REMOTE_ADDR']
    'NotifyUrl' => $myUrl . '/OrderNotifyRequest.php', // url where payu service will send notification with order processing status changes
    'OrderCancelUrl' => $myUrl . 'http://www.wildeast.pl/orders.php',
    'OrderCompleteUrl' => 'http://www.wildeast.pl/orders.php',
    'Order' => $order,
    'ShippingCost' => array(
        'AvailableShippingCost' => $shippingCost,
        'ShippingCostsUpdateUrl' => $myUrl . '/ShippingCostRetrieveRequest.php' // this is url where payu checkout service will send shipping costs retrieve request
    )
);

// echo($myUrl . '/OrderNotifyRequest.php');
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
    'CustomerId' => 'asd',
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
if ($result->getSuccess()) {

    echo '<h4>Debug console</h4><pre>';
    OpenPayU_Order::printOutputConsole();
    echo '</pre>';

    $result = OpenPayU_OAuth::accessTokenByClientCredentials();
?>
<form method="GET" action="<?php echo OpenPayU_Configuration::getAuthUrl(); ?>">
    <fieldset>
        <legend>Process with user authentication</legend>
        <p>During this process, you will be asked to login before moving on to the summary.</p>
        <input type="hidden" name="redirect_uri" value="<?php echo $myUrl . "/BeforeSummaryPage.php";?>">
        <input type="hidden" name="response_type" value="code">
        <input type="hidden" name="client_id" value="<?php echo OpenPayU_Configuration::getClientId(); ?>">
        <p><input type="submit" class="btn btn-primary" value="Next step (user authorization) >"></p>
    </fieldset>
</form>

<form method="GET" action="<?php echo OpenPayu_Configuration::getSummaryUrl();?>">
    <fieldset>
        <legend>Process without user authentication, redirect to summary</legend>
        <p>During this process, you will be taken to a summary</p>
        <input type="hidden" name="sessionId" value="<?php echo $_SESSION['sessionId'];?>">
        <input type="hidden" name="oauth_token" value="<?php echo $result->getAccessToken();?>">
        <input type="hidden" name="order_id" value="1">
        <input type="hidden" name="orderId" value="2">
        <input type="hidden" name="customer_id" value="3">
         <input type="hidden" name="customerId" value="4">
        <p>
            <label for="showLoginDialogSelect">Show login dialog:</label>
            <select name="showLoginDialog" id="showLoginDialogSelect">
                <option value="False">No</option>
                <option value="True">Yes</option>
            </select>
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