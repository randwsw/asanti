<?php



// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$name = mysql_real_escape_string($_POST['name']);
$description = mysql_real_escape_string($_POST['description']);
$headPhotoUrl = mysql_real_escape_string($_POST['headPhotoUrl']);
// //////////////////////////////////////////////////////////////////////////////////////////////////// //



// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }




$sql="INSERT INTO item (name, description, headPhotoUrl)
VALUES
('$name','$description','$headPhotoUrl')";

if (!mysqli_query($conn,$sql))
  {
  die('Error: ' . mysqli_error($conn));
  }
echo "1 record added";





mysqli_close($conn);
header('Location: ../addItem.php');
?>