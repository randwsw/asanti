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
	// GET ALL SIZES -------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$sizeOf = $_POST['sizeOf'];
		$sizeList = array();

		$result = mysqli_query($conn,"SELECT * FROM size WHERE sizeOf = '$sizeOf' ORDER BY value ASC");
		while($row = mysqli_fetch_array($result))
			{
				echo("<input type='checkbox' class='sizeCheckbox' name='pickSize[]' value='" . $row['id'] . "'>" . $row['value'] . "cm</br>");
			}
		mysqli_close($conn);
		break;
		
		
		
		
		
	case "getItemSizes":
	// ---------------------------------------------------------------------------------------------------------- //
	// GET ITEM SIZES ------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$itemId = $_POST['itemId'];
	
		$result = mysqli_query($conn,"SELECT * FROM size_item WHERE itemId = $itemId");
			
		while($e=mysqli_fetch_assoc($result))
	    	$output[]=$e;
	    print(json_encode($output));
		
		mysqli_close($conn);
		break;
	
	
	
	
	
	case "addValue":
	// ---------------------------------------------------------------------------------------------------------- //
	// ADD NEW SIZE VALUE --------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$name = $conn->real_escape_string($_POST['name']);
		$name = $purifier->purify($name);
		
		$value = $_POST['value'];
		
		$sizeOf = $conn->real_escape_string($_POST['sizeOf']);
		$sizeOf = $purifier->purify($sizeOf);
		
		
		$sql="INSERT INTO size (value, name, sizeOf)
		VALUES
		('$value','$name','$sizeOf')";
		
		if (!mysqli_query($conn,$sql))
		  {
			  die('Error: ' . mysqli_error($conn));
			  mysqli_close($conn);
		  }else
		  {
		  	mysqli_close($conn);
		  }
		break;
		
		
		
		
		
		case "delete":
		// ---------------------------------------------------------------------------------------------------------- //
		// DELETE SIZE ---------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$sizeId = $_POST['sizeId'];
			
			$sql2="DELETE FROM size_item
					WHERE sizeId = '$sizeId'";
			
			if (!mysqli_query($conn,$sql2))
			{
		  		die('Error: ' . mysqli_error($conn));
		  		mysqli_close($conn);
		  	}else
		  	{
		  	}
			
	  		$sql="DELETE FROM size
					WHERE id = '$sizeId'";
			if (!mysqli_query($conn,$sql))
			{
		  		die('Error: ' . mysqli_error($conn));
		  		mysqli_close($conn);
		  	}else
		  	{
		  	}
			
			
			mysqli_close($conn);
			break;
		
		
		
		
		
		case "changeSize":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE SIZE POOL ----------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$id = $_POST['id'];
			
			$value = $conn->real_escape_string($_POST['value']);
			$value = $purifier->purify($value);
			
			$newName = $conn->real_escape_string($_POST['newSizeName']);
			$newName = $purifier->purify($newName);
			
			$sizeOf = $conn->real_escape_string($_POST['sizeOf']);
			$sizeOf = $purifier->purify($sizeOf);
			
			$option = "";
			
			$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
			$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
			$newSizeOf =  str_replace($aWhat, $aOn, $newName);
			
			$option = $_POST['option'];
			
			
			
			if ($option == "sizeOf"){
				$result = mysqli_query($conn,"SELECT COUNT(name) AS count FROM size WHERE sizeOf = '$sizeOf'");
					while($row = mysqli_fetch_array($result))
						{
							$count = $row['count'];
						}
						
				for($i=0; $i < $count; $i++){
					mysqli_query($conn,'UPDATE size SET name = "' . $newName . '", sizeOf = "' . $newSizeOf . '" WHERE sizeOf = "' . $sizeOf . '"');
				}
				
			}else{
				if ($option == "id"){
					mysqli_query($conn,'UPDATE size SET value = "' . $value . '" WHERE id = "' . $id . '"');
				}	
			}
			mysqli_close($conn);
			break;
			
			
			
			
			
		case "changeItemSize":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE ITEM SIZES ---------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$itemId = $_POST['itemId'];
			$sizeId = $_POST['sizeId'];
			$active = $_POST['active'];
			
			if($active == "1"){
				mysqli_query($conn,"INSERT INTO size_item (sizeId, itemId) VALUES ($sizeId, $itemId)");
			}else{
				if($active == "0"){
					mysqli_query($conn,"DELETE FROM size_item WHERE itemId = $itemId AND sizeId = $sizeId");
				}
			}
			
			mysqli_close($conn);
			break;
			
			
			
			
			
		case "addSize":
		// ---------------------------------------------------------------------------------------------------------- //
		// ADD NEW SIZE TYPE ---------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$name = $_POST['name'];
			$value = $_POST['value'];
			
			$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
						$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
						$sizeOf =  str_replace($aWhat, $aOn, $name);
			
			
				$sql="INSERT INTO size (value, name, sizeOf)
					VALUES
					('$value','$name','$sizeOf')";
					
					if (!mysqli_query($conn,$sql))
					  {
						  die('Error: ' . mysqli_error($conn));
						  mysqli_close($conn);
					  }else
					  {
					  	mysqli_close($conn);
					  }
			break;
}
?>