<?php

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$id = $_POST['categoryId'];
$name = $_POST['categoryName'];


$aWhat = array('Ą', 'Ę', 'Ó', 'Ś', 'Ć', 'Ń', 'Ź', 'Ż', 'Ł', 'ą', 'ę', 'ó', 'ś', 'ć', 'ń', 'ź', 'ż', 'ł', ',', ' ');
$aOn =    array('A', 'E', 'O', 'S', 'C', 'N', 'Z', 'Z', 'L', 'a', 'e', 'o', 's', 'c', 'n', 'z', 'z', 'l', '', '_');
$urlName =  str_replace($aWhat, $aOn, $name);

// echo($id . "___" . $name . "___" . $urlName);
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  
// ENCODING TO UTF8
$sql = "SET NAMES 'utf8'";
!mysqli_query($conn,$sql);



	mysqli_query($conn,'UPDATE category SET name = "' . $name . '", urlName = "' . $urlName . '" WHERE id = "' . $id . '"');


mysqli_close($conn);
?>