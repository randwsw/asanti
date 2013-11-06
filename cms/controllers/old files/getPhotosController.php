<?php


$itemId = $_POST['itemId'];
class photo {
	public $id;
	public $url;
}
										
$photoList = array();		
				
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
										
										
if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}						
	
	
$result3 = mysqli_query($conn,"SELECT * FROM photo WHERE item_id = $itemId AND isHEadPhoto = '0' ORDER BY orderN ASC");
										
while($row3 = mysqli_fetch_array($result3))
	{
		$photo = new photo;
		$photo->id = $row3['id'];
		$photo->url = $row3['url'];
		array_push($photoList, $photo);
	}

foreach($photoList as $photo){
	echo("<li>
			<div class='frame'><span class='helper'></span>
				<img src='$photo->url' class='photoThumb' id='itemPhoto$photo->id'/>
			</div><div class='moveBefore' id='moveBefore$photo->id'><</div><div class='deletePhoto' id='deletePhoto$photo->id'>X</div><div class='moveAfter' id='moveAfter$photo->id'>></div>
	</li>")	;
}
mysqli_close($conn);
?>