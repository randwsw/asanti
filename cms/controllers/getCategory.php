<?php

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$parentId = $_POST['parentId'];

class category{
	public $id;
	public $parentId;
	public $name;
}
$categoryList = array();
// //////////////////////////////////////////////////////////////////////////////////////////////////// //


// ENCODING TO UTF8
$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);


if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		$result = mysqli_query($conn,"SELECT * FROM category WHERE parentId = '$parentId'");
		
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($conn));
		    exit();
		}
		
		// while($e=mysqli_fetch_assoc($result))
		              // $output[]=$e;
		           // print(json_encode($output));
// 		
		
		
		
		
		
		while($row = mysqli_fetch_array($result))
			{
				$category = new category;
				$category->id = $row['id'];
				$category->parentId = $row['parentId'];
				$category->name = $row['name'];  		
				array_push($categoryList, $category);
			}
		
		// results
		
		if($parentId == 0){
			$categorySelect = "categoryRoot";
		}else{
			$categorySelect = "categoryToPost";
		}
		
		echo("<select name='" . $categorySelect . "' id='categorySelectLevel_" . $parentId . "'>");
		foreach($categoryList as $c){
			echo("<option value='" . $c->id . "'>");
			echo($c->name);
			echo("</option>");
		}
		echo("/select>");
		
	mysqli_close($conn);
?>

