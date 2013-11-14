<?php 

/**
 *	OrderNotifyRequest message processing and retrieve order details.
 *
 *	@copyright  Copyright (c) 2011-2012, PayU
 *	@license    http://opensource.org/licenses/GPL-3.0  Open Software License (GPL 3.0)		
 */

include_once("sdk/openpayu.php");
include_once("config.php");

// helper function, just to save some output to file
function write_to_file($file, $data) {
	file_put_contents($file, $data, FILE_APPEND);
};

try {	

	$result = OpenPayU_Order::consumeMessage($_POST['DOCUMENT']);
	if ($result->getMessage() == 'OrderNotifyRequest') {
		$result = OpenPayU_Order::retrieve($result->getSessionId());
		write_to_file("debug.txt", "order details: \n\n " . serialize($result->getResponse()) . "\n\n");
		
		$fh = fopen('log.txt', 'a') or die("Can't open file.");
		$results = print_r($result->getResponse(), true);
		fwrite($fh, $results);
		fclose($fh);	

		$arr = array();
		$arr = $result->getResponse();
		$status = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['OrderStatus'];
		$sesid = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['SessionId'];
		$shippcost = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Shipping']['ShippingCost']['Gross'];
		$shipptype = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Shipping']['ShippingType'];
		$dbstatus = 0;
		switch ($status) {
		    case "ORDER_STATUS_PENDING":
		       	$dbstatus = 1;
		        break;
		    case "ORDER_STATUS_COMPLETE":
		        $dbstatus = 2;
		        break;
		    case "ORDER_STATUS_CANCEL":
		        $dbstatus = 3;
		        break;
		}
		/*BILLING*/
		$btin=null;$banumber=null;$sanumber=null;
		$bstreet = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Invoice']['Billing']['Street'];
		$bhnumber = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Invoice']['Billing']['HouseNumber'];
		$banumber = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Invoice']['Billing']['ApartmentNumber'];
		$bpcode = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Invoice']['Billing']['PostalCode'];
		$bcity = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Invoice']['Billing']['City'];
		$bcc = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Invoice']['Billing']['CountryCode'];
		$btin = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Invoice']['Billing']['TIN'];
		$bname = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Invoice']['Billing']['RecipientName'];
		/*SHIPPING*/
		$sstreet = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Shipping']['Address']['Street'];
		$shnumber = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Shipping']['Address']['HouseNumber'];
		$sanumber = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Shipping']['Address']['ApartmentNumber'];
		$spcode = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Shipping']['Address']['PostalCode'];
		$scity = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Shipping']['Address']['City'];
		$sname = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Shipping']['Address']['RecipientName'];
		
		if($banumber==null) {
			$banumber=0;
		}
		if($sanumber==null) {
			$sanumber=0;
		}
		
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		mysqli_set_charset($conn, "utf8");
		
		if (mysqli_connect_errno())
		{
	 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		$shippcost = $shippcost/100;
		$asd = "UPDATE orders SET status=$dbstatus, shipping_value=$shippcost, shipping_name='$shipptype' WHERE status <= 1 AND id =(SELECT order_id FROM payu_conn WHERE sesid='$sesid') ";
		
		/*UPDATE SHIPPIN */
		mysqli_query($conn,"UPDATE orders SET status=$dbstatus, shipping_value=$shippcost, shipping_name='$shipptype' WHERE status <= 1 AND id =(SELECT order_id FROM payu_conn WHERE sesid='$sesid') ");
		write_to_file("query.txt", $asd. "\n\n");
		
		
		/*GET ORDER ID */
		$result = mysqli_query($conn,"SELECT order_id FROM payu_conn WHERE sesid='$sesid'");
		while($e = mysqli_fetch_array($result)) {
			$order_id = $e["order_id"];
		}
		
		/*CHECK FOR DUPLICATES */
		$result = mysqli_query($conn,"SELECT COUNT(*) AS count FROM shipping_address WHERE order_id='$order_id'");
			while($e = mysqli_fetch_array($result)) {
				$c = $e["count"];
			}
		/*ADD IF NO DUPLICATES */	
		if($c==0) {
		mysqli_query($conn,"INSERT INTO shipping_address (pcode, street, city, hnum, anum, order_id, name)
				VALUES ('$spcode', '$sstreet', '$scity', '$shnumber', $sanumber, $order_id, '$sname')");
		}
		
		/*CHECK IF EXISTS */			
		if($btin!=null) {
			/*GET USER ID */
			$result = mysqli_query($conn,"SELECT user_id FROM orders WHERE id='$order_id'");
			while($e = mysqli_fetch_array($result)) {
				$user_id = $e["user_id"];
			}
			write_to_file("oneval.txt", $bpcode." ".$bstreet." ".$bcity." ".$bhnumber." ".$banumber." ".$user_id." ".$order_id." ".$btin."\n\n");
			/*CHECK FOR DUPLICATES */
			$result = mysqli_query($conn,"SELECT COUNT(*) AS count FROM billing_address WHERE order_id='$order_id'");
			while($e = mysqli_fetch_array($result)) {
				$c = $e["count"];
			}
			/*ADD IF NO DUPLICATES */	
			if($c==0) {
				mysqli_query($conn,"INSERT INTO billing_address (pcode, street, city, hnum, anum, user_id, order_id, NIP, name)
				VALUES ('$bpcode', '$bstreet', '$bcity', '$bhnumber', $banumber, $user_id, $order_id, '$btin', '$bname')");
			} else {
				
			}
		}
		
		
		
		
		
		mysqli_close($conn);
	}		
} catch (Exception $e) {
	write_to_file("debug.txt", $e->getMessage());
	write_to_file("debug.txt", OpenPayU_Order::printOutputConsole());
}