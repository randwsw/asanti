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
	<div class="logfiller">
		
	<!-- Include background animation ------------------------------------------- -->
	<!-- <?php include 'include/backanim.php'; ?> -->
	<!-- ------------------------------------------------------------------------ -->
	<img src="img/forkids2.png" id="log-logo">
		<div class="logreg-div">
			
			<div class="log-div">
					
					<h2>Zaloguj się</h2>
					<div class ="formdiv">
						<p>Masz już konto? Wpisz swoje dane.</p>
					</div>
					<div class ="formdiv">
						<p>Adres email</p>
						<input type='text' class="form-text-input" id="login-input"/>
					</div>
					<div class ="formdiv">
						<p>Hasło</p>
						<input type='password' class="form-text-input" id="password-input"/>
					</div>
					<div class ="formdiv">
						<div class ="formdivcolumn">
							<input class="form-button" type="button" value="Zaloguj się" />
						</div>
						<div class ="formdivcolumn" id="logpcheckbox">
							<p> Zapamiętaj mnie</p>
						</div>
						<div class ="formdivcolumn" id="logcheckbox">
							<input type="checkbox" ></input>
						</div>
					</div>
					<div class ="formdiv">
						<p>Nie pamiętasz swojego hasła?</p> <a href="../asanti/login.php"><p>Kliknij tutaj.</p></a>
					</div>
			</div>
			
			<div class ="reg-div">
				<h2>Zarejestruj się</h2>
				<div class ="formdiv">
					<p>Nie masz konta? Kliknij poniżej.</p>
				</div>
				<div class ="formdiv">
					<ul>
			            <li>&#187 Costam1</li>
			            <li>&#187 Costam2</li>
			            <li>&#187 Costam3</li>
			            <li>&#187 Costam4</li>
			            <li>&#187 Costam5</li>
			        </ul>
			    </div>	
				<div class ="formdiv">
					<a href="../asanti/register.php"><input class="form-button" type="button" value="Utwórz konto" /></a>
				</div>
				<div class ="formdiv" id="backdiv">
					<a href="../asanti/shop.php"><p id="pback">&#171 wróć do sklepu</p></a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
		$('#login-input').watermark("Wpisz tutaj swój adres email");
		$('#password-input').watermark("Wpisz tutaj swoje hasło");
</script>