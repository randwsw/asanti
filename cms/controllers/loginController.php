<?php

// if(!session_id()){
	// session_start();
// } 

// include '../include/checkLog.php';

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

require_once '../../htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

// //////////////////////////////////////////////////////////////////////////////////////////////////// //



$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);


if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }


$action = $_POST['action'];

switch ($action) {
	
	case "login":
	// ---------------------------------------------------------------------------------------------------------- //
	// Login -------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //	
		$login = $conn->real_escape_string($_POST['login']);
		$login = $purifier->purify($login);
		
		$password1 = $conn->real_escape_string($_POST['password1']);
		$password1= $purifier->purify($password1);
		
		$check = 0;
		
		
		$result = mysqli_query($conn,"SELECT COUNT(*) AS checkLog FROM control WHERE log = '$login' AND pass = '$password1'");
				while($e = mysqli_fetch_array($result))
				  {
						$check = $e['checkLog'];
				  }
		if($check == 1)	 
		{
			session_start();
			if(isset($_SESSION['login'])){
		  		unset($_SESSION['login']);
				unset($_SESSION['status']);
			}
			$_SESSION['log']=$login;
			$_SESSION['status']="adm";
		}
		else {
			print("Zły login, hasło lub nieaktywne konto");
		}
		echo($check);
		mysqli_close($conn);
	break;

}


?>