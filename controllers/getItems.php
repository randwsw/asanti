<?php



// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
// $name = mysql_real_escape_string($_POST['name']);
// $description = mysql_real_escape_string($_POST['description']);
// $headPhotoUrl = mysql_real_escape_string($_POST['headPhotoUrl']);
// //////////////////////////////////////////////////////////////////////////////////////////////////// //



// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


$sql= mysqli_query($conn, "SELECT name, value, url FROM item i, price pr, photo ph WHERE i.headPhotoId = ph.id;") or die(mysql_error());


while($rec = mysqli_fetch_array($sql)) {
  	echo $rec['name']."<br />";
} 



mysqli_close($conn);
//header('Location: ../addItem.php');
?>