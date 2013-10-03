<?php
if(!session_id())
	{
	    session_start();
	} 
//session_destroy();
//Zrobic usuwanie pojedynczych elementow
if(isset($_SESSION['login']))
  unset($_SESSION['login']);
if(isset($_SESSION['status']))
  unset($_SESSION['status']);
header("Location: ../login.php");
?>