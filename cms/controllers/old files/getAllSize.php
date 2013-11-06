<?php


// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$sizeOf = $_POST['sizeOf'];
$sizeList = array();
// //////////////////////////////////////////////////////////////////////////////////////////////////// //



if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		$result = mysqli_query($conn,"SELECT * FROM size WHERE sizeOf = '$sizeOf' ORDER BY value ASC");
		while($row = mysqli_fetch_array($result))
		  {
		  		echo("<input type='checkbox' class='sizeCheckbox' name='pickSize[]' value='" . $row['id'] . "'>" . $row['value'] . "cm</br>");
				// array_push($sizeList, $row['value']);
		  }
	mysqli_close($conn);
?>