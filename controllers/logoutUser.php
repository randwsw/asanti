<?php
if(!session_id())
	{
	    session_start();
	} 
//session_destroy();
//Zrobic usuwanie pojedynczych elementow
if(isset($_SESSION['login']))
  unset($_SESSION['login']);
if(isset($_SESSION['name']))
  unset($_SESSION['name']);
if(isset($_SESSION['lastname']))
  unset($_SESSION['lastname']);
?>