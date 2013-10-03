<?php
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$name = $_POST['categoryName'];
$parentId = $_POST['parentId'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //


$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
$urlName =  str_replace($aWhat, $aOn, $name);



// ADD CATEGORY
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }



// ENCODING TO UTF8
$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);




$result = mysqli_query($conn,"SELECT catLevel FROM category WHERE id = '$parentId'");
		
		while($row = mysqli_fetch_array($result))
			{
				$catLevel = $row['catLevel'];
			}
			// echo($parentId . "</br>");
			// echo($catLevel);
$catLevel +=1 ;
// echo("</br>" . $catLevel);

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




?>