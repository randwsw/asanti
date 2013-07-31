<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    <script type="text/javascript" src="js/jquery.watermark.min.js"></script>


    
    <link rel="stylesheet" href="css/shopstyle.css" />
    <link rel="stylesheet" href="css/sliderstyle.css" />
    
</head>

<body>
	<div class="regfiller">
		
	<!-- Include background animation ------------------------------------------- -->
	<!-- <?php include 'include/backanim.php'; ?> -->
	<!-- ------------------------------------------------------------------------ -->
	<img src="img/forkids2.png" id="log-logo">
		<div class="register-div">
					
				<form method="POST" target="regFunction.php" class="regform">
					<h2>Rejestracja</h2>
					<div class ="formdiv">
						<p>Adres email</p>
						<input type='text' class="form-text-input" id="email-input"/>
					</div>
					<div class ="formdiv">
						<p>Hasło</p>
						<input type='password' class="form-text-input" id="pass-input"/>
					</div>
					<div class ="formdiv">
						<p>Potwierdź hasło</p>
						<input type='password' class="form-text-input" id="pass2-input"/>
					</div>
										
					<div class ="formdiv">
						<div class="formdivcolumn">
							<p>Imię</p>
							<input type='text' class="form-text-input-s" id="name-input"/>
						</div>
						<div class="formdivcolumn" id="lastname">
							<p>Nazwisko</p>
							<input type='text' class="form-text-input-s" id="lastname-input"/>
						</div>
					</div>
					
					<div class ="formdiv">
						<div class="formdivcolumn" id="divstreet">
							<p>Ulica</p>
							<input type='text' class="form-text-input" id="street"/>
						</div>
						<div class="formdivcolumn" id="divpcode">
							<p>Kod poczt.</p>
							<input type='text' class="form-text-input" id="pcode"/>
						</div>
					</div>
					<div class ="formdiv">
						<p>Miejscowość</p>
						<input type='text' class="form-text-input" id="city-input"/>
					</div>
					<div class ="formdiv">
						<p>Telefon</p>
						<input type='text' class="form-text-input" id="phone-input"/>
					</div>
					<div class ="formdiv">
						<input class="form-button" type="button" value="Zaloguj się" />
						<a href="../asanti/shop.php"><p id="pback">&#171 wróć do sklepu</p></a>
					</div>
					
				</form>
				
			</div>						
	</div>
</body>
</html>
<script type="text/javascript">
		$('#email-input').watermark("Wpisz swój adres email");
		$('#pass-input').watermark("Wpisz swoje hasło");
		$('#pass2-input').watermark("Ponownie wpisz hasło");
		$('#name-input').watermark("Wpisz swoje imię");
		$('#lastname-input').watermark("Wpisz swoje nazwisko");
		$('#street').watermark("Ulica i nr domu / mieszkania");
		$('#pcode').watermark("xx - xxx");
		$('#city-input').watermark("Wpisz swoje miasto / miejscowość");
		$('#phone-input').watermark("Wpisz swój numer telefonu");

</script>