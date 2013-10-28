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
	// GET ALL CONNECTIONS -------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$sets = " AND i.id IN (SELECT item1_id FROM item_conn) OR i.id IN (SELECT item2_id FROM item_conn)";

		if(isset($_POST['id'])){
			$idFilter = " AND i.id = '" . $_POST['id'] . "'";
		}else{$idFilter = "";}
		
		
		
		if(isset($_POST['name'])){
			$nameFilter = " AND i.name = '" . $_POST['name'] . "'";
		}else{$nameFilter = "";}
		
		
		
		if(isset($_POST['category'])){
			$category = $_POST['category'];
			if($category == null){
				$categoryFilter = "";
			}else{
				$result2 = mysqli_query($conn,"SELECT catLevel FROM category WHERE id = '$category'");
				while($row2 = mysqli_fetch_array($result2))
					{
						
						$c = $row2['catLevel'];
						// echo($c . "</br>");
						if($c == "1"){
							$categoryFilter = " AND c.id IN (SELECT id FROM category WHERE parentId = '$category')";
							
						}else{
							$categoryFilter = " AND c.id = '$category'";
						}
					}
			}
				
		}else{$categoryFilter = "";}
		
		
		
		if(isset($_POST['sortBy'])){
			$sortBy = $_POST['sortBy'];
			$direction = $_POST['direction'];
			if($sortBy == null || $direction == null){
				$sort = " ORDER BY i.name ASC";
			}else{
				$sort = " ORDER BY $sortBy $direction";
			}
		}else{
			$sort = " ORDER BY i.name ASC";
		}
		// //////////////////////////////////////////////////////////////////////////////////////////////////// //
		
		
		$result = mysqli_query($conn,"SELECT i.id AS itemId, i.name AS itemName, i.description AS itemDescription, 
										i.active AS itemActive, ph.url AS headPhotoUrl, i.price AS itemPrice, 
										c.name AS categoryName, c.urlName AS categoryUrlName, (SELECT COUNT(id) FROM item_conn ic WHERE i.id = ic.item1_id OR i.id = ic.item2_id) AS connections
									FROM item i, category c, category_con cc, photo ph
									WHERE c.id = cc.cat_id
									AND i.id = cc.item_id"
									. $idFilter . $nameFilter . $categoryFilter . //$sets .
									" GROUP BY i.id"
									. $sort);
				
		while($e=mysqli_fetch_assoc($result))
			$output[]=$e;
		print(json_encode($output));
		mysqli_close($conn);
		break;
		
		
		
		
	
		case "add":
		// ---------------------------------------------------------------------------------------------------------- //
		// ADD NEW CONNECTION --------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$itemId1 = $_POST['itemId1'];
			$itemId2 = $_POST['itemId2'];
			
			$sql="INSERT INTO item_conn (item1_id, item2_id)
			VALUES
			('$itemId1', '$itemId2')";
			
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
		// DELETE CONNECTION ---------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$itemId1 = $_POST['itemId1'];
			$itemId2 = $_POST['itemId2'];
			
			$sql="DELETE FROM item_conn
					WHERE (item1_id = '$itemId1' AND item2_id = '$itemId2') OR (item1_id = '$itemId2' AND item2_id = '$itemId1')";
			if (!mysqli_query($conn,$sql))
			{
				  die('Error: ' . mysqli_error($conn));
				  mysqli_close($conn);
			}else
			{
				echo("success");
				mysqli_close($conn);
			}
			break;
			
			
			
			
			
		case "deleteAll":
		// ---------------------------------------------------------------------------------------------------------- //
		// DELETE ALL CONNECTIONS ----------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$itemId2 = $_POST['itemId2'];
			
			$result = mysqli_query($conn,"SELECT id FROM item_conn WHERE (item1_id = $itemId2 OR item2_id = $itemId2)");
												
			while($row = mysqli_fetch_array($result))
				{
					$id = $row['id'];
					$sql="DELETE FROM item_conn
					WHERE id = '$id'";
					if (!mysqli_query($conn,$sql))
					{
						  die('Error: ' . mysqli_error($conn));
						  mysqli_close($conn);
					}else
					{
						echo("success");
					}	
				}
			
			mysqli_close($conn);
			break;
}
?>