<?php

if(!session_id()){
	session_start();
} 

include '../include/checkLog.php';

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








if(isset($_POST['sets'])){
	if($_POST['sets'] == "sets"){
		$itemId = $_POST['itemId'];
		$sets = " AND i.id != $itemId AND i.id NOT IN (SELECT ic.item2_id FROM item_conn ic WHERE ic.item1_id = $itemId)
										AND i.id NOT IN (SELECT ic.item1_id FROM item_conn ic WHERE ic.item2_id = $itemId)";
	}else{
		$sets = "";
	}
}else{
	$sets = "";
}




if(isset($_POST['id'])){
	$idFilter = " AND i.id = '" . $_POST['id'] . "'";
}else{$idFilter = "";}




if(isset($_POST['name'])){
	$nameFilter = " AND i.name = '" . $_POST['name'] . "'";
}else{$nameFilter = "";}










if(isset($_POST['action']) && $_POST['action'] == "getUsers"){

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
}
		

if(isset($_POST['action']) && $_POST['action'] == "changeActive"){
	
	$userId = $_POST['userId'];
	$active = $_POST['active'];
	
	mysqli_query($conn,"UPDATE users SET active=$active WHERE id='$userId'");

	mysqli_close($conn);
}


if(isset($_PSOT['action']) && $_POST['action'] == "changePw"){
	$userId = $_POST['userId'];
	$newPw = $_POST['newPW'];
	
	mysqli_query($conn,"UPDATE users SET password='$newPw' WHERE id='$userId'");

	mysqli_close($conn);
}

?>