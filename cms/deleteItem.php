<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}
?>

<?php

if(!isset($_POST["submit"])){?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	<link rel="stylesheet" href="../css/cms.css" type="text/css" />
	<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
	<?php include "../include/links.php"; ?>

	<script type="text/javascript">
	
	function getItems(){
		$.ajax({ 
		    type: 'POST', 
		    url: 'controllers/getItems.php', 
		    data: {},
		    dataType: 'json',
		    error: function (data) {
		    	// alert("error");
		    },
		    success: function (data) { 
		    	$.each(data,function(i,row){
		    		
		    		var selectValues = {};
		    		
		    		selectValues[row.itemId] = row.itemName;
		    		
		    		$.each(selectValues, function(key, value) {
					    $('#itemToDelete').append($("<option/>", {
					        value: key,
					        text: value
					    }));
					});

		    	})
		    	
			},
		})
	}
	
	
	$(document).ready(function(){
		getItems();
	})
	</script>
  
</head>




<body>
	<div id="container">
		<div id="header"><div id="cmsTitle">ASANTI CMS</div><div id="cmsSubTitle">Dodaj zdjęcia</div></div>
		<div id="content">
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			<div id="rightContent">
				<div id="rightContentContainer">
					<input type="button" onclick="history.go(-1)" value="Powrót" />
					<form action="deleteItem.php"; method="POST" enctype="multipart/form-data">
						<div id="selectDeleteItemBox">
							<select id="itemToDelete" name="itemToDelete">
								
							</select>
						</div>
						<input type="submit" name="submit" value="Usuń" />
					</form>
				</div>				
			</div>
		</div>	
		<div id="cmsFooter">ASANTI CMS FOOTER</div>
	</div>
</body>
	
	
	
	
	
	
	
	
	
		
</html>

<?php }
else 
{
			
			
// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn2=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn3=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn4=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$itemToDelete = mysql_real_escape_string($_POST['itemToDelete']);
// //////////////////////////////////////////////////////////////////////////////////////////////////// //




// DELETE PHOTOS

  	
  	$sql="DELETE FROM photo
			WHERE item_id = '$itemToDelete'";
		if (!mysqli_query($conn,$sql))
		{
	  		die('Error: ' . mysqli_error($conn));
	  		mysqli_close($conn);
	  	}else
	  	{
			mysqli_close($conn);
	  	}
	
	
  
  
// DELETE CATEGORY CONNECTIONS

  	$sql2="DELETE FROM category_con
			WHERE item_id = '$itemToDelete'";
		if (!mysqli_query($conn2,$sql2))
		{
	  		die('Error: ' . mysqli_error($conn2));
	  		mysqli_close($conn2);
	  	}else
	  	{
			mysqli_close($conn2);
	  	}
	
	

// DELETE SIZE ITEM CONNECTIONS

$sql3="DELETE FROM size_item
			WHERE itemId = '$itemToDelete'";
		if (!mysqli_query($conn3,$sql3))
		{
	  		die('Error: ' . mysqli_error($conn3));
	  		mysqli_close($conn3);
	  	}else
	  	{
			mysqli_close($conn3);
	  	}
	
	
	

// DELETE ITEM

  	$sql4="DELETE FROM item
			WHERE id = '$itemToDelete'";
		if (!mysqli_query($conn4,$sql4))
		{
	  		die('Error: ' . mysqli_error($conn4));
	  		mysqli_close($conn4);
	  	}else
	  	{
			mysqli_close($conn4);
	  	}
	
	
	
// DELETE FILES AND DIR ON FTP SERVER

set_time_limit(300);//for uploading big files
	
	$paths="/asanti/img/items/" . $itemToDelete;

	$ftp_server="serwer1309748.home.pl";

	$ftp_user_name="serwer1309748";

	$ftp_user_pass="9!c3Q9";

	$filesList = array();



	// set up a connection to ftp server
	$conn_id = ftp_connect($ftp_server);
	
	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

	// check connection and login result
	if ((!$conn_id) || (!$login_result)) {
		echo "FTP connection has encountered an error!";
		echo "Attempted to connect to $ftp_server for user $ftp_user_name....";
		exit;
	   	} else {
	       	echo "Connected to $ftp_server, for user $ftp_user_name".".....";
	   	}
   
   
	

	ftp_chdir($conn_id, $paths);
	$files = ftp_nlist($conn_id, ".");
	foreach ($files as $file)
	{
	    ftp_delete($conn_id, $file);
	}    
		ftp_rmdir($conn_id, $paths);
		// close the FTP connection
		ftp_close($conn_id);	
	
	
	header("Location: items.php");
}
?>