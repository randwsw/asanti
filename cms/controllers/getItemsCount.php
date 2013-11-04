<?php

// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
// //////////////////////////////////////////////////////////////////////////////////////////////////// //


if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		 
			$cat="";
			$ct='all';
			if(isset($_GET['category'])) {
				$cat = " AND (c.id = ".$_GET['category']." OR c.parentId=".$_GET['category'].")";
				$ct = $_GET['category'];
			} else {
				
			}
			if($ct=='all'){
				$cat='';
			}
	  
		  $sql2= mysqli_query($conn, "SELECT count(*) AS count 
		  FROM item i, photo ph, category c, category_con cc 
		  WHERE i.headPhotoId = ph.id AND i.active = 1 AND cc.item_id = i.id AND cc.cat_id =c.id  $cat;") or die(mysql_error());
		  
		  while($rec2 = mysqli_fetch_array($sql2)) {
			$count =  $rec2['count'];
		}
?>