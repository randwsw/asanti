<html>
	<div>
		<form action="image.php" method="post" enctype="multipart/form-data">
			<label for="file">Filename:</label>
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Submit">
			
		</form>
	</div>
</html>
<?php
if(isset($_POST['submit'])){
	// File and new size
	$filename = 'cukrzyca.jpg';
	$percent = 0.5;
	
	// Content type
	header('Content-Type: image/jpeg');
	
	// Get new sizes
	list($width, $height) = getimagesize($filename);
	$newwidth = $width * $percent;
	$newheight = $height * $percent;
	
	// Load
	$thumb = imagecreatetruecolor($newwidth, $newheight);
	$source = imagecreatefromjpeg($filename);
	
	// Resize
	imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	
	// Output
	imagejpeg($thumb);
}
?>