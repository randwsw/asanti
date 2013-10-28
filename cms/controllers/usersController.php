<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}


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
	
	case "getAll":
	// ---------------------------------------------------------------------------------------------------------- //
	// GET ALL USERS -------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //

		if(isset($_POST['sortBy'])){
			$sortBy = $_POST['sortBy'];
			$direction = $_POST['direction'];
			if($sortBy == null || $direction == null){
				$sort = " ORDER BY email ASC";
			}else{
				$sort = " ORDER BY $sortBy $direction";
			}
		}else{
			$sort = " ORDER BY email ASC";
		}		
		
		$result = mysqli_query($conn,"SELECT u.id AS id, u.email AS email, u.name AS name, u.lastName AS lastName, u.password AS password, u.active AS active,
										 a.pcode AS pcode, a.street AS street, a.city AS city 
									FROM users u, address a
									WHERE u.id = a.user_id 
									GROUP BY u.id"
									 . $sort);
			
			while($e=mysqli_fetch_assoc($result))
	              $output[]=$e;
	           print(json_encode($output));
		mysqli_close($conn);
	break;



	
	case "deleteUser":
	// ---------------------------------------------------------------------------------------------------------- //
	// DELETE USER ---------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$userId = $_POST['userId'];
		
		  		$sql="DELETE FROM address
						WHERE user_id = '$userId'";
				if (!mysqli_query($conn,$sql))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  	}
				
				
				$sql="DELETE FROM orders
						WHERE user_id = '$userId'";
				if (!mysqli_query($conn,$sql))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  	}
				
				
				$sql="DELETE FROM phone
						WHERE user_id = '$userId'";
				if (!mysqli_query($conn,$sql))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  	}
				
				
				$sql="DELETE FROM remember_me
						WHERE user_id = '$userId'";
				if (!mysqli_query($conn,$sql))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  	}
				
				
				$sql="DELETE FROM usr_activate
						WHERE user_id = '$userId'";
				if (!mysqli_query($conn,$sql))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  	}
				
				
				$sql="DELETE FROM users
						WHERE id = '$userId'";
				if (!mysqli_query($conn,$sql))
				{
			  		die('Error: ' . mysqli_error($conn));
			  		mysqli_close($conn);
			  	}else
			  	{
			  	}
				
				
				mysqli_close($conn);
	break;
	
	
	
	
	
	case "changeActive":
	// ---------------------------------------------------------------------------------------------------------- //
	// CHANGE ACTIVE -------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	
		$userId = $_POST['userId'];
		$active = $_POST['active'];
		
		mysqli_query($conn,"UPDATE users SET active=$active WHERE id='$userId'");
	
		mysqli_close($conn);
	break;


	
	
	case "changePw":
	// ---------------------------------------------------------------------------------------------------------- //
	// CHANGE USER PASSWORD ------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$userId = $_POST['userId'];
		$newPw = $_POST['newPw'];
		
		mysqli_query($conn,"UPDATE users SET password='$newPw' WHERE id='$userId'");
	
		mysqli_close($conn);
	break;
	
	
	
	
	
	case "update":
	// ---------------------------------------------------------------------------------------------------------- //
	// UPDATE USER'S INFO --------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$userId = $conn->real_escape_string($_POST['userId']);
		
		$email = $conn->real_escape_string($_POST['email']);
		$email = $purifier->purify($email);
		
		$name = $conn->real_escape_string($_POST['name']);
		$name = $purifier->purify($name);
		
		$lastName = $conn->real_escape_string($_POST['lastName']);
		$lastName = $purifier->purify($lastName);
		
		$pcode = $conn->real_escape_string($_POST['pcode']);
		
		$street = $conn->real_escape_string($_POST['street']);
		$street = $purifier->purify($street);
		
		$city = $conn->real_escape_string($_POST['city']);
		$city = $purifier->purify($city);
									
		$sql = "SET NAMES 'utf8'";
		!mysqli_query($conn,$sql);
									
									
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
  		mysqli_query($conn,"UPDATE users SET email='$email', name='$name', lastName='$lastName' WHERE id='$userId'");
		mysqli_query($conn,"UPDATE address SET pcode='$pcode', street='$street', city='$city' WHERE user_id='$userId'");
		mysqli_close($conn);

		break;
}


?>