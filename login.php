<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    
</head>

<body>
	<div class="filler">
		
	<div class="container">
	<!-- Include background animation ------------------------------------------- -->
	<!-- <?php include 'include/backanim.php'; ?> -->
	<!-- ------------------------------------------------------------------------ -->
	<img src="img/forkids2.png" id="log-logo">
		<div class="logreg-div">
			
			<div class="log-div">
				<h2>Zaloguj się</h2>
				<p>Masz już konto? Wpisz swoje dane.</p>
				<br>
				<p>Adres email</p>
				<input type='text' class="form-text-input" id="login-input"/>
				<p>Hasło</p>
				<input type='password' class="form-text-input" id="password-input"/>
				<div class='log-div-button'>
					<input class="form-button" type="button" value="Zaloguj się" />
					<div>
					<input type="checkbox" ><p> Zapamiętaj mnie</p></input>
					</div>
				</div>
				<p>Nie pamiętasz swojego hasła?</p> <a href="../asanti/login.php"><p>Kliknij tutaj.</p></a>
			</div>
			
			<div class ="reg-div">
				<h2>Zarejestruj się</h2>
				<p>Nie masz konta? Kliknij poniżej.</p>
				<br>
				<ul>
		            <li><p>- Costam1</p></li>
		            <li><p>- Costam2</p></li>
		            <li><p>- Costam3</p></li>
		            <li><p>- Costam4</p></li>
		            <li><p>- Costam5</p></li>
		        </ul>	
				<div>
					<a href="../asanti/register.php"><input class="form-button" type="button" value="Utwórz konto" /></a>
				</div>
			</div>
			<a href="../asanti/shop.php"><p id="back-p">>>wróć do sklepu</p></a>
		</div>
	</div>
	</div>
</body>
</html>
<script type="text/javascript">
		$('#login-input').watermark("Wpisz tutaj swój adres email");
		$('#password-input').watermark("Wpisz tutaj swoje hasło");
</script>