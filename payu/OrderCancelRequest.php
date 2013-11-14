<?php
if(!session_id())
	{
	    session_start();
	}

/**
 *	Order cancel testing, it might be used independly from the main payment process.
 *
 *	@copyright  Copyright (c) 2011-2012, PayU
 *	@license    http://opensource.org/licenses/GPL-3.0  Open Software License (GPL 3.0)
 */

include_once('sdk/openpayu.php');
include_once('config.php');


    if(!empty($_GET['id']))
    {
    	$id = $_GET['id'];
		$email = $_SESSION['login'];
    	$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		mysqli_set_charset($conn, "utf8");
		
		if (mysqli_connect_errno())
			{
		 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		
		$result = mysqli_query($conn,"SELECT sesid FROM payu_conn WHERE order_id=$id AND user_id = (SELECT id FROM users WHERE email='$email')");
		
		while($e = mysqli_fetch_array($result)) {
			echo($e['sesid']);
			OpenPayU_Order::cancel($e['sesid'], true);
		}
		mysqli_close($conn);
       

    }
    else
    {
  		header("Location: orders.php");
    }
?>
