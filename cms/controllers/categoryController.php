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
	
	
	
	
    case "getCategories":
	// ---------------------------------------------------------------------------------------------------------- //
	// GET ALL CATEGORIES --------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //		
		$parentId = $_POST['parentId'];

		class category{
			public $id;
			public $parentId;
			public $name;
		}
		$categoryList = array();
		
		
		$result = mysqli_query($conn,"SELECT * FROM category WHERE parentId = '$parentId'");
		
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($conn));
		    exit();
		}
		
	
		while($row = mysqli_fetch_array($result))
			{
				$parentId = $row['parentId'];
				if($parentId == 0){
					$categorySelect = "categoryRoot";
				}else{
					$categorySelect = "categoryToPost";
				}
				$id = $row['id'];
				$name = $row['name'];
				echo("<input type='radio' value='" . $id . "' name='" . $categorySelect . "'>" . $name . "</br>");
			}
		
		mysqli_close($conn);
		break;




	case "getItemCategory":
	// ---------------------------------------------------------------------------------------------------------- //
	// GET ITEM CATEGORY ---------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
	// ---------------------------------------------------------------------------------------------------------- //
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
		break;
	
	
	
	
	
	case "add":
		// ---------------------------------------------------------------------------------------------------------- //
		// ADD CATEGORY --------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$name = $conn->real_escape_string($_POST['categoryName']);
		$name = $purifier->purify($name);
		
		$parentId = $conn->real_escape_string($_POST['parentId']);
		$parentId = $purifier->purify($parentId);
		
		// //////////////////////////////////////////////////////////////////////////////////////////////////// //
		
		
		$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
		$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
		$urlName =  str_replace($aWhat, $aOn, $name);
		
		
		$result = mysqli_query($conn,"SELECT catLevel FROM category WHERE id = '$parentId'");
		
		while($row = mysqli_fetch_array($result))
			{
				$catLevel = $row['catLevel'];
			}
			
		$catLevel +=1 ;
		
		$sql="INSERT INTO category (name, parentId, catLevel, urlName)
		VALUES
		('$name','$parentId','$catLevel', '$urlName')";
		
		if (!mysqli_query($conn,$sql))
		  {
			  die('Error: ' . mysqli_error($conn));
			  mysqli_close($conn);
		  }else
		  {
		  	mysqli_close($conn);
		  }
		break;
		
		
		
		
		
		case "change":
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE CATEGORY ------------------------------------------------------------------------------------------ //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$id = $conn->real_escape_string($_POST['categoryId']);
		
		$name = $conn->real_escape_string($_POST['categoryName']);
		$name = $purifier->purify($name);

		$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
		$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
		$urlName =  str_replace($aWhat, $aOn, $name);
		
		mysqli_query($conn,'UPDATE category SET name = "' . $name . '", urlName = "' . $urlName . '" WHERE id = "' . $id . '"');

		mysqli_close($conn);
		break;
		
		
		
		
		
		case "changeItemCategory": // probably not used //
		// ---------------------------------------------------------------------------------------------------------- //
		// CHANGE ITEM CATEGORY ------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$itemId = $_POST['itemId'];
		$sizeId = $_POST['categoryId'];
		
		mysqli_query($conn,"UPDATE category_con SET cat_id = $categoryId WHERE item_id = $itemId");


		mysqli_close($conn);
		break;
		
		
		
		
		
		case "delete":
		// ---------------------------------------------------------------------------------------------------------- //
		// DELETE CATEGORY ------------------------------------------------------------------------------------------ //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		// ---------------------------------------------------------------------------------------------------------- //
		$categoryId = $_POST['categoryId'];
		$count = "";
		$count2 = "";
		
		$result = mysqli_query($conn,"SELECT COUNT(*) AS catCount FROM category_con WHERE cat_id = '$categoryId'");
		
		while($row = mysqli_fetch_array($result))
			{
				$count = $row['catCount'];
			}

		$result2 = mysqli_query($conn,"SELECT COUNT(*) AS catCount2 FROM category WHERE parentId = '$categoryId'");
				
			while($row2 = mysqli_fetch_array($result2))
				{
					$count2 = $row2['catCount2'];
				}
				
				
		// DELETE CATEGORY
	  	if($count != 0 || $count2 != 0){
	  		echo("failed");
	  	}else{
	  		$sql="DELETE FROM category
					WHERE id = '$categoryId'";
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
}





?>