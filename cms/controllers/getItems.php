<?php


// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

if(isset($_POST['id'])){
	$idFilter = " AND i.id = '" . $_POST['id'] . "'";
}else{$idFilter = "";}

if(isset($_POST['name'])){
	$nameFilter = " AND i.name = '" . $_POST['name'] . "'";
}else{$nameFilter = "";}

if(isset($_POST['category'])){
	$categoryFilter = " AND c.name = '" . $_POST['category'] . "'";
}else{$categoryFilter = "";}
// //////////////////////////////////////////////////////////////////////////////////////////////////// //




if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		$result = mysqli_query($conn,"SELECT i.id AS itemId, i.name AS itemName, i.description AS itemDescription, 
										ph.url AS headPhotoUrl, i.price AS itemPrice, c.name AS categoryName, c.urlName AS categoryUrlName
									FROM item i, category c, category_con cc, photo ph
									WHERE c.id = cc.cat_id
									AND i.id = cc.item_id
									AND i.headPhotoId = ph.url"
									. $idFilter . $nameFilter . $categoryFilter .
									" GROUP BY i.name 
									ORDER BY i.name ASC");
		
		while($e=mysqli_fetch_assoc($result))
              $output[]=$e;
           print(json_encode($output));
	mysqli_close($conn);




?>