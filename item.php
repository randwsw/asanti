<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>

    
    <link rel="stylesheet" href="css/shopstyle.css" />
    
    
    
    
    
    
<?php




// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$itemId = $_GET['id'];
$conn1=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
$conn2=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");


// class item {
	// public $id;
	// public $name;
	// public $description;
	// public $headPhotoUrl;
// }
// 
// 
// $itemList = array();
// //////////////////////////////////////////////////////////////////////////////////////////////////// //







// Get news /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$result1 = mysqli_query($conn1,"SELECT COUNT(*) AS count FROM item WHERE id = '$itemId'");
while($row1 = mysqli_fetch_array($result1))
{
	if($row1['count'] != 1){
		header('Location: shop.php');
	}else{
		
	$result2 = mysqli_query($conn2,"SELECT * FROM item WHERE id = '$itemId'");

	while($row1 = mysqli_fetch_array($result2))
		{
			// $item = new item;
			// $item->id = $row1['id'];
			// $item->name = $row1['name'];
			// $item->description = $row1['description'];
			// $item->headPhotoUrl = $row1['headPhotoUrl'];
			// array_push($itemList, $item);
			$name = $row1['name'];
			$description = $row1['description'];
			$headPhotoUrl = $row1['headPhotoUrl'];
		}
		mysqli_close($conn1);
		mysqli_close($conn2);
}
}



// //////////////////////////////////////////////////////////////////////////////////////////////////// //
	
	
	
	
	
// Output ///////////////////////////////////////////////////////////////////////////////////////////// //
	
	// echo ('<select id="selectClasses">
			// <option value="0">Wszystkie zajęcia</option>');
	// foreach($itemList as $item) {
		// echo ('<option value="' . $classes->id . '">' . $classes->name . '</option>');
	// }
	// echo ('</select>');
	
// //////////////////////////////////////////////////////////////////////////////////////////////////// //
	


?>
    
    
    
    
    
</head>

<body>

    <div class="container">
    	
	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	
	<div id="itemContainer">
		<div id="itemPhotoContainer">
			<img id="itemBigPhoto" src="<?php echo("$headPhotoUrl"); ?>"/>
		</div>
		<div id="itemDescriptionContainer">
			<h2 id="itemTitle"><?php echo("$name"); ?><h2>
			<div id="itemDescription"><?php echo("$description"); ?></div>
		</div>
	</div>
	 </div>
   
</body>      
</html>
<script type="text/javascript">
	
$( document ).ready(function() {


	$(function() {			
	    $(".menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
	    
	    $(".sub-menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
			
		// var $tlt = jQuery.noConflict();
		// $tlt(function() {
		// $tlt('.lavamenu').lavalamp();
		// });
	});
	
	
});

$('.imageContainer').mouseenter(function() {
   $(this).children('.imageOverlay').fadeIn(100, function() {
   		$(this).children('.imageOverlay').stop();
   });
});

$('.imageContainer').mouseleave(function() {
	$(this).children('.imageOverlay').fadeOut(100, function() {
		$(this).stop();
});
});


</script>

