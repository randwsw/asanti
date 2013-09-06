<?php

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$id = $_POST['id'];
$value = $_POST['value'];
$newName = $_POST['newSizeName'];
$sizeOf = $_POST['sizeOf'];
$option = "";

$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
$newSizeOf =  str_replace($aWhat, $aOn, $newName);
// //////////////////////////////////////////////////////////////////////////////////////////////////// //





// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  
  
  
// ENCODING TO UTF8
$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);


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
	// mysqli_query($conn,'UPDATE category SET name = "' . $name . '", urlName = "' . $urlName . '" WHERE id = "' . $id . '"');


mysqli_close($conn);
?>