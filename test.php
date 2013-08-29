<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
	
#content {
	width: 500px;
	height: 500px;
	background-color: rgba(0,0,0,0.3);
	text-align: center;
	
}	

.helper {
	display: inline-block;
    height: 100%;
    vertical-align: middle;
}

img {
	vertical-align: middle;
}
	
</style>

<?php include "include/links.php"; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('input[name=qwe][value=22]').prop("checked",true);
	});
</script>
</head>

	<body>
		<form action="test.php"; method="POST" enctype="multipart/form-data">
		<div id="content"> <span class="helper"></span>
			<input type="radio" name="qwe" value="11"/>
			<input type="radio" name="qwe" value="22"/>
			<input type="radio" name="qwe" value="33"/>
			<input type="submit" value="submit"/>
		</div>
	   </form>
	</body>     
	
	 
</html>
<?php

echo($_POST['qwe']);

?>













<?php 



if($_POST['submit']){

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");

$itemId = $_POST['itemId'];
$categoryId = $_POST['categoryToPost'];
$itemName = $_POST['name'];
$itemDescription = $_POST['description'];
$itemPrice = $_POST['price'];
// //////////////////////////////////////////////////////////////////////////////////////////////////// //

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }




	mysqli_query($conn,"UPDATE item SET name = $itemName, description = $itemDescription, price = $itemPrice WHERE id = $itemId");
	mysqli_query($conn,"UPDATE category_con SET cat_id = $categoryId WHERE item_id = $itemId");



mysqli_close($conn);
}



?>