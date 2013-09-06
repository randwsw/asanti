<?php

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$itemId = $_POST['itemId'];
$rootId = 0;
$parentId = "";
$categoryId = "";

class category{
	public $id;
	public $parentId;
	public $name;
}
$categoryList = array();
$categoryList2 = array();
// //////////////////////////////////////////////////////////////////////////////////////////////////// //


// ENCODING TO UTF8
$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);


if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		
		
		
		
		$result = mysqli_query($conn,"SELECT c.id AS categoryId, c.parentId AS parentId FROM category_con cc, category c WHERE cc.item_id = $itemId AND c.id = cc.cat_id");
		
		while($row = mysqli_fetch_array($result))
			{
				$categoryId = $row['categoryId'];
				$parentId = $row['parentId'];
			}
			
			
			
			
			
		$result2 = mysqli_query($conn,"SELECT * FROM category WHERE parentId = '$rootId'");
		
		if (!$result2) {
		    printf("Error: %s\n", mysqli_error($conn));
		    exit();
		}
		
		while($row2 = mysqli_fetch_array($result2))
			{
				$category = new category;
				$category->id = $row2['id'];
				$category->parentId = $row2['parentId'];
				$category->name = $row2['name'];  		
				array_push($categoryList, $category);
			}
		
		
		
		
		
		
		$result3 = mysqli_query($conn,"SELECT * FROM category WHERE parentId = '$parentId'");
		
		if (!$result3) {
		    printf("Error: %s\n", mysqli_error($conn));
		    exit();
		}
		
		while($row3 = mysqli_fetch_array($result3))
			{
				$category2 = new category;
				$category2->id = $row3['id'];
				$category2->parentId = $row3['parentId'];
				$category2->name = $row3['name'];  		
				array_push($categoryList2, $category2);
			}
		
		
		
		
		
		// results
		
		if($parentId == 0){
			$categorySelect = "categoryRoot";
		}else{
			$categorySelect = "categoryToPost";
		}
		
		
		
		
		echo("<div id='categoryLevel_0'>");
		foreach($categoryList as $c){
			if($c->id == $parentId){
				echo("<input type='radio' value='" . $c->id . "' name='categoryRoot' checked>" . $c->name . "</br>");
			}else{
				echo("<input type='radio' value='" . $c->id . "' name='categoryRoot'>" . $c->name . "</br>");
			}
			
		}
		echo("</div>");
		
		
		
		echo("<div id='categoryLevel_2'>");
		foreach($categoryList2 as $c2){
			if($c2->id == $categoryId){
				echo("<input type='radio' value='" . $c2->id . "' name='categoryToPost' checked>" . $c2->name . "</br>");
			}else{
				echo("<input type='radio' value='" . $c2->id . "' name='categoryToPost'>" . $c2->name . "</br>");
			}
			
		}
		echo("</div>");
		
	mysqli_close($conn);
?>

<!-- $result = mysqli_query($conn,"SELECT c.id AS categoryId, c.parentId AS parentId FROM category_con cc, category c WHERE cc.item_id = $itemId AND c.id = cc.cat_id");
		
		while($e=mysqli_fetch_assoc($result))
              $output[]=$e;
           print(json_encode($output));
	mysqli_close($conn); -->