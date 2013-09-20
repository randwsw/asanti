<?php


// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

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




if(isset($_POST['category'])){
	$category = $_POST['category'];
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
}else{$categoryFilter = "";}




		
		$result = mysqli_query($conn,"SELECT i.id AS itemId, i.name AS itemName, i.description AS itemDescription, 
										i.active AS itemActive, ph.url AS headPhotoUrl, i.price AS itemPrice, 
										c.name AS categoryName, c.urlName AS categoryUrlName
									FROM item i, category c, category_con cc, photo ph
									WHERE c.id = cc.cat_id
									AND i.id = cc.item_id"
									. $idFilter . $nameFilter . $categoryFilter . $sets .
									" GROUP BY i.id 
									ORDER BY i.name ASC");
		
		while($e=mysqli_fetch_assoc($result))
              $output[]=$e;
           print(json_encode($output));
	mysqli_close($conn);




?>