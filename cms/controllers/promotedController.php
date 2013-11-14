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
	// GET ALL PROMOTED ----------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
		$promoted = " AND i.id IN (SELECT item_id FROM recommended)";

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
		
		
		$result = mysqli_query($conn,"SELECT i.id AS itemId, i.name AS itemName,  
										i.active AS itemActive, ph.url AS headPhotoUrl, i.price AS itemPrice, rp.price AS recPrice, r.id AS recId, 
										c.name AS categoryName, c.urlName AS categoryUrlName, (SELECT COUNT(id) FROM item_conn ic WHERE i.id = ic.item1_id OR i.id = ic.item2_id) AS connections
									FROM item i, category c, category_con cc, photo ph, rec_price rp, recommended r
									WHERE c.id = cc.cat_id
									AND i.id = cc.item_id
									AND r.id = rp.rec_id
									AND r.item_id = i.id"
									. $idFilter . $nameFilter . $categoryFilter . $promoted .
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
			$itemId2 = $_POST['itemId2'];
			
			$result = mysqli_query($conn,"SELECT i.price AS itemPrice FROM item i WHERE i.id = $itemId2");
			while($row = mysqli_fetch_array($result))
			{
				$price = $row['itemPrice'];
			}
			
			$sql="INSERT INTO recommended (item_id)
			VALUES
			('$itemId2')";
			
			if (!mysqli_query($conn,$sql))
			{
				die('Error: ' . mysqli_error($conn));
				mysqli_close($conn);
			}else
			{
			}
			
			$result2 = mysqli_query($conn,"SELECT r.id AS recId FROM recommended r ORDER BY r.id DESC LIMIT 1");
			while($row2 = mysqli_fetch_array($result2))
			{
				$recId = $row2['recId'];
			}
			$sql2="INSERT INTO rec_price (rec_id, price)
			VALUES
			('$recId', '$price')";
			
			if (!mysqli_query($conn,$sql2))
			{
				die('Error: ' . mysqli_error($conn));
				mysqli_close($conn);
			}else
			{
			}
			mysqli_close($conn);
			break;
			  
			  
			  
			  
			  
		case "deleteAll":
		// ---------------------------------------------------------------------------------------------------------- //
		// DELETE ALL CONNECTIONS ----------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$itemId2 = $_POST['itemId2'];
					
					$result = mysqli_query($conn,"SELECT r.id AS recId FROM recommended r WHERE r.item_id = $itemId2");
					while($row = mysqli_fetch_array($result))
					{
						$recId = $row['recId'];
					}
					
					$sql="DELETE FROM rec_price
					WHERE rec_id = '$recId'";
					if (!mysqli_query($conn,$sql))
					{
						  die('Error: ' . mysqli_error($conn));
						  mysqli_close($conn);
					}else
					{
					}		
					
					$sql="DELETE FROM recommended
					WHERE id = '$recId'";
					if (!mysqli_query($conn,$sql))
					{
						  die('Error: ' . mysqli_error($conn));
						  mysqli_close($conn);
					}else
					{
					}

			mysqli_close($conn);
			break;
			
			
			
			
			
		case "changePrice":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE PROMOTED ITEM PRICE ------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
			$recId = $_POST['recId'];
			$price = $_POST['price'];
			
			mysqli_query($conn,'UPDATE rec_price SET price = "' . $price . '" WHERE rec_id = "' . $recId . '"');
			mysqli_close($conn);
			break;
}
?>