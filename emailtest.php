<?php
$activationKey = md5(time().date("Y:m:d"));

// $conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
// 
// if (mysqli_connect_errno())
	// {
 		// echo "Failed to connect to MySQL: " . mysqli_connect_error();
	// }
// 
// mysqli_query($conn,"INSERT INTO usr_activate (user_id, user_key)
// VALUES (8, '$activationKey')");

$to = "rrandzor@gmail.com";
$subject = "Aktywacja konta";
$message = "<html>
				<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Aktywacja konta użytkownika</title>
				</head>
				<body>
				<div>Kliknij w link poniżej, aby aktywować konto.</div>
				<a href='controllers/activate.php?userkey=$activationKey'> controllers/activate.php?userkey=$activationKey</a>
				</body>
			</html>";
$from = "someonelse@example.com";
$headers = "From:" . $from;
//mail($to,$subject,$message,$headers);
//echo "Mail Sent.";
echo($activationKey);
echo($message);
?> 