<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	
	<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
		
	<script type="text/javascript">
		
		function login(){
			$('input[name="loginButton"]').click(function(){
				var login = $("input[name='login']").val();
				var password1 = $("input[name='password1']").val();;
				$.ajax({ 
					type: 'POST', 
					url: 'controllers/loginController.php', 
					data: {action: "login", login : login, password1 : password1},
						 
					error: function (data) {
						// alert("porażka!");					
						},
					success: function (data) {
						if(data == 1){
							window.location.replace("index.php");
						}else{
							if(data != 1){
								window.location.replace("login.php");
							}
						}
					},
				});
			});
		}
		
		$(document).ready(function(){
			login();
		});
		
	</script>
</head>

<body>
	<div id="cmsLogin">
		<form action="login.php" method="POST">
			<div id="box">
				<img src="../img/nextlogo.png" />
				<div id="loginRow">
					<div class="title">Login:</div>
					<input type="text" name="login" />
				</div>
				<div id="passwordRow">
					<div class="title">Hasło:</div>
					<input type="password" name="password1" />
				</div>
				<input type="button" value="Zaloguj się" name="loginButton" />
			</div>
		</form>
	</div>
	

</body>      
</html>

	<?php if(isset($_POST['submit'])){
		ob_start();
		require_once '../htmlpurifier/library/HTMLPurifier.auto.php';

		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		
		require_once("../include/config.php");
		$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
		
		$login = $conn->real_escape_string($_POST['login']);
		$login = $purifier->purify($login);
		
		$password1 = $conn->real_escape_string($_POST['password1']);
		$password1= $purifier->purify($password1);
		
		$check = 0;
		
		// Get news /////////////////////////////////////////////////////////////////////////////////////////// //
		if (mysqli_connect_errno())
			{
		 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		
		$count="asd";
		// if($rememberMe == "true")
		// {
			// $result = mysqli_query($conn,"SELECT COUNT(*) AS count FROM remember_me rm, users u WHERE rm.user_id = u.id AND u.email='$email'");
				// while($e = mysqli_fetch_array($result))
				  // {
						// $count= $e['count'];
				  // }
		// }
		
		$result = mysqli_query($conn,"SELECT COUNT(*) AS checkLog FROM control WHERE log = '$login' AND pass = '$password1'");
				while($e = mysqli_fetch_array($result))
				  {
						$check = $e['checkLog'];
				  }
		if($check == 1)	 
		{
			session_start();
			if(isset($_SESSION['login'])){
		  		unset($_SESSION['login']);
				unset($_SESSION['status']);
			}
			$_SESSION['log']=$login;
			$_SESSION['status']="adm";

			
			
			
		}
		else {
			print("Zły login, hasło lub nieaktywne konto");
		}
		mysqli_close($conn);
		header("Location: index.php");
		ob_end_flush();
	}
	?>