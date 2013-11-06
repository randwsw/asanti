<?php
	if(isset($_POST['email'])) {
		$email = $_POST['email'];
	} else {
		$email = $_SESSION['login'];
	}
	$subject = $_POST['title'];
	$message = $_POST['Text1'];
	
	
	$to = "pytania@wildeast.pl";
	$from = "no-reply@asanti.com";
	$headers = "From:" . $email;
mail($to,$subject,$message,$headers);
?>