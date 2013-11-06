<?php

	$email = $_POST['email'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$message = nl2br($message);
	
	$headerFields = array(
    "From: {$email}",
    "MIME-Version: 1.0",
    "Content-Type: text/html;charset=utf-8"
	);
	
	$to = "pytania@wildeast.pl";
	$from = "no-reply@asanti.com";
	$headers = "From:" . $email."\r\n Content-type: text/html; charset=UTF-8";
    mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',$message, implode("\r\n", $headerFields));
    // echo($email." ".$subject." ".$message);
?>