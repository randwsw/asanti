<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    <script type="text/javascript" src="js/jquery.watermark.min.js"></script>


    
    <link rel="stylesheet" href="css/shopstyle.css" />
    <link rel="stylesheet" href="css/sliderstyle.css" />
    
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
				<form method="POST" target="regFunction.php" class="regform">
					<h2>Rejestracja</h2>
					<br>
					<p>Adres email</p>
					<input type='text' class="form-text-input" />
					<p>Hasło</p>
					<input type='password' class="form-text-input" />
					<p>Potwierdź hasło</p>
					<input type='password' class="form-text-input" />
					<div class="double-div">
						<div class="small-div">
							<p>Imię</p>
							<input type='text' class="form-text-input-small" />
						</div>
						<div class="separator"></div>
						<div class="small-div">
							<p>Nazwisko</p>
							<input type='text' class="form-text-input-small" />
						</div>
					</div>
					<div class="double-div">
						<div class="small-div">
							<p>Ulica</p>
							<input type='text' class="form-text-input" id="street"/>
						</div>
						<div class="separator"></div>
						<div class="small-div">
							<p>Kod pocztowy</p>
							<input type='text' class="form-text-input" id="pcode"/>
						</div>
					</div>
					<p>asd</p>
					<input type='text' class="form-text-input" />
					<p>Telefon</p>
					<input type='text' class="form-text-input" />
					<br>
					<input class="form-button" type="button" value="Zaloguj się" />
					<a href="../asanti/shop.php"><p id="back-p">&#171 wróć do sklepu</p></a>
				</form>
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