<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
require_once("../../include/config.php");
$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);

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
	
	
	case "addColor":
		// ---------------------------------------------------------------------------------------------------------- //
		// ADD COLOR ------------------------------------------------------------------------------------------------ //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$name = $conn->real_escape_string($_POST['name']);
		$name = $purifier->purify($name);
		
		// //////////////////////////////////////////////////////////////////////////////////////////////////// //
		
		
		$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
		$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
		$colorOf =  str_replace($aWhat, $aOn, $name);
		
		
		$sql="INSERT INTO colors (name, colorOf)
		VALUES
		('$name','$colorOf')";
		
		if (!mysqli_query($conn,$sql))
		  {
			  die('Error: ' . mysqli_error($conn));
			  mysqli_close($conn);
		  }else
		  {
		  	mysqli_close($conn);
		  }
		break;
		
		
		
		
		
		case "changeColor":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE COLOR --------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$id = $conn->real_escape_string($_POST['id']);
		
		$name = $conn->real_escape_string($_POST['name']);
		$name = $purifier->purify($name);

		$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
		$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
		$colorOf =  str_replace($aWhat, $aOn, $name);
		
		mysqli_query($conn,'UPDATE colors SET name = "' . $name . '", colorOf = "' . $colorOf . '" WHERE id = "' . $id . '"');

		mysqli_close($conn);
		break;
		
		
		
		
		
		case "deleteColor":
		// ---------------------------------------------------------------------------------------------------------- //
		// DELETE COLOR --------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$id = $_POST['id'];
		
		// $result = mysqli_query($conn,"SELECT COUNT(*) AS catCount FROM category_con WHERE cat_id = '$categoryId'");
// 		
		// while($row = mysqli_fetch_array($result))
			// {
				// $count = $row['catCount'];
			// }
// 
		// $result2 = mysqli_query($conn,"SELECT COUNT(*) AS catCount2 FROM category WHERE parentId = '$categoryId'");
// 				
			// while($row2 = mysqli_fetch_array($result2))
				// {
					// $count2 = $row2['catCount2'];
				// }
// 				
				
		// DELETE CATEGORY
	  	// if($count != 0 || $count2 != 0){
	  		// echo("failed");
	  		if(1 == 2){
	  	}else{
	  		$sql="DELETE FROM colors
					WHERE id = '$id'";
			if (!mysqli_query($conn,$sql))
			{
		  		die('Error: ' . mysqli_error($conn));
		  		mysqli_close($conn);
		  	}else
		  	{
		  		echo("success");
				mysqli_close($conn);
		  	}
	  	}
		break;
		
		
		
		
		
		case "changeItemColor":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE ITEM COLOR ---------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$itemId = $_POST['itemId'];
		$colorId = $_POST['colorId'];
		$active = $_POST['active'];
			
		if($active == "1"){
			mysqli_query($conn,"INSERT INTO colors_conn (color_id, item_id) VALUES ($colorId, $itemId)");
		}else{
			if($active == "0"){
				mysqli_query($conn,"DELETE FROM colors_conn WHERE item_id = '$itemId' AND color_id = '$colorId'");
			}
		}
			
		mysqli_close($conn);	
		break;
}





?>