<?php if(!session_id()) { session_start();} ?>
<?php
	if(!isset($_SESSION['login'])) {
		header("Location: index.php");
	} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	
    <link rel="stylesheet" href="css/borders/profileborders.css" />
    
</head>

<body>
	<!-- Include background animation ------------------------------------------- -->
	<?php include 'include/backanim.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<div class="bg">
	</div>
	<div class="bg" id="bg2"></div> 
	<div class="bigdiv">
		<div class="rowdiv" id="topdiv">
		</div>
		<div class="rowdiv" id="middiv">
			<div class="rightdiv" id="midrightdiv">
				
			</div>
			<div class="centerdiv" id="midcenterdiv">
				</div>
			<div class="leftdiv" id="midleftdiv">
				
			</div>
		</div>
		<div class="rowdiv" id="botdiv">
		</div>
	</div>
				
<?php
	if(!session_id())
	{
	    session_start();
	}
	if(!isset($_SESSION['login'])) {
	}else {
		
		require_once 'htmlpurifier/library/HTMLPurifier.auto.php';

		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		
		$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
		mysqli_set_charset($conn, "utf8");
		
		$login = $conn->real_escape_string($_SESSION['login']);
		$login= $purifier->purify($login);
		
		$returnValue = null;


		if (mysqli_connect_errno())
			{
		 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		
		
		
		$result = mysqli_query($conn,"SELECT pValue, email, password, name, lastname, pcode, street, city FROM users u, address a, phone p WHERE email = '$login' AND p.user_id = u.id AND a.user_id = u.id");
				while($e = mysqli_fetch_array($result))
				  {
						$phone = $e['pValue'];
						$name= $e['name'];
						$lastname = $e['lastname'];
						$pcode = $e['pcode'];
						$street = $e['street'];
						$city = $e['city'];
						$password = $e['password'];
				  }
		// echo($name." ".$lastname);
		mysqli_close($conn);
	} 

	
	echo("
	<div class='regfiller'>
		
		<div class='updateleftCol'>
		<a href='index.php'><img src='img/nextlogo.png' id='log-logo'></a>
		
	
				<form method='POST' class='profileform' id='regform' >
					<div id='phead'><a id='em'>( ".$login." )</a><a id='or' href='orders.php'>Moje zamówienia</a></div>
					<br>
														
					<div class ='formdiv'>
						<div class='formdivcolumn'>
							<p>Imię</p>
							<input type='text' class='form-text-input-s' id='name-input' name='name' value='".$name."'/>
							<div class='errordiv' id='name_label'></div>
						</div>
						<div class='formdivcolumn' id='lastname'>
							<p>Nazwisko</p>
							<input type='text' class='form-text-input-s' id='lastname-input' name='lastname' value='".$lastname."'/>
							<div class='errordiv' id='lastname_label'></div>
						</div>
					</div>
					
					
					<div class ='formdiv'>
						<div class='formdivcolumn' id='divstreet'>
							<p>Ulica</p>
							<input type='text' class='form-text-input' id='street' name='street' value='".$street."'/>
							<div class='errordiv' id='street_label'></div>
						</div>
						<div class='formdivcolumn' id='divpcode'>
							<p>Kod poczt.</p>
							<input type='text' class='form-text-input' id='pcode' name='pcode' value='".$pcode."'/>
							<div class='errordiv' id='pcode_label'></div>
						</div>
					</div>
					
					<div class ='formdiv'>
						<p>Miejscowość</p>
						<input type='text' class='form-text-input' id='city-input' name='city' value='".$city."'/>
						<div class='errordiv' id='city_label'></div>
					</div>					
					
					<div class ='formdiv'>
						<p>Telefon</p>
						<input type='text' class='form-text-input' id='phone-input' name='phone' value='".$phone."'/>
						<div class='errordiv' id='phone_label'></div>
					</div>
										
					<div class ='formdiv'>
						<input class='form-button' type='submit' value='Aktualizuj' id='regButton'/>
					</div>
					
				</form>
				
			</div>
			<div class='updaterightCol'>
				
				<div class='passwordUpdate'>
				<br>
					<form method='POST' class='passform'>
						<div class ='formdiv'>
							<p>Nowe hasło</p>
							<input type='password' class='form-text-input' id='pass-input' name='password1'/>
							<div class='errordiv' id='password1_label'></div>
						</div>
						<div class ='formdiv'>
							<p>Potwierdź nowe hasło</p>
							<input type='password' class='form-text-input' id='pass2-input' name='password2'/>
							<div class='errordiv' id='password2_label'></div>
						</div>
						<div class ='formdiv'>
							<input class='form-button' type='submit' value='Aktualizuj' id='passButton'/>
							<a href='index.php'><p id='pback'>&#171 wróć do sklepu</p></a>
						</div>	
					</form>
				</div>
			</div>						
	</div>
	");
	?>
	<div class="big2div" id="addcart">
				<div class="row2div" id="top2div">
				</div>
				<div class="row2div" id="mid2div">
					<div class="right2div" id="midright2div">
						
					</div>
					<div class="center2div" id="midcenter2div">
						<p id="main">Hasło zostało pomyślnie zauktualizowane. Za chwilę zostaniesz wylogowany/a. Zaloguj się ponownie.</p>
					</div>
					<div class="left2div" id="midleft2div">
						
					</div>
				</div>
				<div class="row2div" id="bot2div">
				</div>
	</div>
	<div class="big2div" id="updateuser">
				<div class="row2div" id="top2div">
				</div>
				<div class="row2div" id="mid2div">
					<div class="right2div" id="midright2div">
						
					</div>
					<div class="center2div" id="midcenter2div">
						<p id="main">Dane zostały pomyślnie zaktualizowane.</p>
						<p id="ret"><a>powrót</a></p>
					</div>
					<div class="left2div" id="midleft2div">
						
					</div>
				</div>
				<div class="row2div" id="bot2div">
				</div>
	</div>	
</body>
</html>
<script type="text/javascript">
$( document ).ready(function() {

	var height = $( '#midcenterdiv' ).css( "height" );
	$( '#midrightdiv, #midleftdiv, #middiv' ).css( "height", height );
});

		$('#email-input').watermark("Wpisz swój nowy adres email");
		$('#newemail2-input').watermark("Ponownie wpisz adres email");
		$('#pass-input').watermark("Wpisz swoje hasło");
		$('#pass2-input').watermark("Ponownie wpisz hasło");
		$('#name-input').watermark("Wpisz swoje imię");
		$('#lastname-input').watermark("Wpisz swoje nazwisko");
		$('#street').watermark("Ulica i nr domu / mieszkania");
		$('#pcode').watermark("xx - xxx");
		$('#city-input').watermark("Wpisz swoje miasto / miejscowość");
		$('#phone-input').watermark("Wpisz swój numer telefonu");
		
	jQuery.validator.addMethod("customEmail", function(value, element) {
        return this.optional(element) || value.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
    }, "Zły format adresu email!")
    
    jQuery.validator.addMethod("Pcode", function(value, element) {
        return this.optional(element) || value.match(/^[0-9]{2}\-[0-9]{3}$/);
    }, "xx - xxx !")
    
    //^([A-Z][a-z]*((\s)))+[A-Z][a-z]*$
    //^[A-Z][a-z]{1,}$
    //^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$
    jQuery.validator.addMethod("nameLastname", function(value, element) {
        return this.optional(element) || value.match(/^[A-ZŻŹĆĄŚĘŁÓŃ][a-zżźćńółęąś]{1,}$/);
    }, "Nieprawidłowe imię !")
    
    jQuery.validator.addMethod("city", function(value, element) {
        return this.optional(element) || value.match(/^[A-ZŻŹĆĄŚĘŁÓŃ][a-zżźćńółęąś]+(?:[\s-][A-ZŻŹĆĄŚĘŁÓŃ][a-zżźćńółęąś]+)*$/);
    }, "Nieprawidłowa nazwa miejscowości !")
    
    jQuery.validator.addMethod("passW", function(value, element) {
        return this.optional(element) || value.match(/^((?=.*\d)(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[^A-Za-z0-9])(?=.*[a-z])|(?=.*[^A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9]))^.*$/);
    }, "Musi zawierać małą i wielką literę oraz cyfrę, lub znak spec. !")
    
    jQuery.validator.addMethod("phone", function(value, element) {
        return this.optional(element) || value.match(/^([0-9]{9})|(([0-9]{3} ){2}[0-9]{3})|(([0-9]{2} )[0-9]{7})$/);
    }, "Tylko cyfry (9) i/lub znak spacji !")

//^([0-9]{9})|(([0-9]{3}-){2}[0-9]{3})$
jQuery.validator.addMethod("checkUser", function(value, element) {
	var em = $('#email-input').val();
	var user = null;
	
	$.ajax({
        url: "controllers/checkUser.php",
        type: 'post',
        data: {email : em},
        dataType: 'html',
        async: false,
        success: function(data) {
            user = data;
        } 
     });
  return this.optional(element) || value != user;
}, "Ten adres email jest już używany!");

var validate = $(".profileform").validate({
	errorPlacement: function(error, element) {
	    var l = element.attr("name");
	    l='#'+l+"_label";
	    $(l).html( error );
	    
	        
	},
	success: function(label) {
		
	    var l = label.attr("name");
	    l='#'+l;
	    l=l+"_label";
	    $(l).html( "Ok" );    
	    
	},
	submitHandler: function(){
        $.post("controllers/updateUser.php", 
        { name: $("#name-input").val(), lastname: $("#lastname-input").val(), pcode: $("#pcode").val(), 
          street: $("#street").val(), city: $("#city-input").val(), phone: $("#phone-input").val() })
		.done(function(data) {
			$("#updateuser").css("display", "block");
			$("#bg2").css("display", "block");
		});
    },
	rules: {
		email: {
			required: true,
			customEmail: true,
			checkUser: true
		},
		password1: {
			required: true,
			minlength: 8,
			passW: true
		},
		password2: {
			required: true,
			equalTo: "#pass-input"
		},
		name: {
			required: true,
			nameLastname: true		
		},
		lastname: {
			required: true,
			nameLastname: true			
		},
		street: {
			required: true
		},
		pcode: {
			required: true,
			Pcode: true
		},
		city: {
			required: true,
			city: true			
		},
		phone: {
			required: true,
			phone: true,
			minlength: 9	
		}
	},
	 messages: {
		email: {
			required: "Pole email jest puste !",
			customEmail: "Zły format adresu email !",
		},
		password1: {
			required: "Pole hasło jest puste !",
			minlength: jQuery.format("Minimum {0} znaków !")
		},
		password2: {
			required: "Pole hasło jest puste !",
			equalTo: "Podane hasła różnią się !"
		},
		name: {
			required: "Pole imię jest puste !",
			nameLastname: "Nieprawidłowe imię !"
		},
		lastname: {
			required: "Pole nazwisko jest puste !",
			nameLastname: "Nieprawidłowe nazwisko !"		
		},
		street: {
			required: "Pole ulica jest puste !"
		},
		pcode: {
			required: "Brak kodu !"
		},
		city: {
			required: "Pole miasto jest puste !",
			city: "Od wielkiej litery, bez liczb i znaków spec. !"			
		},
		phone: {
			required: "Pole telefon jest puste !",
			number: "Wpisz prawidłowy numer (bez spacji) !",
			minlength: jQuery.format("Minimum {0} znaków !")				
		}
	}
});

var validate2 = $(".emailform").validate({
	errorPlacement: function(error, element) {
	    var l = element.attr("name");
	    l='#'+l+"_label";
	    $(l).html( error );
	  	        
	},
	success: function(label) {
		
	    var l = label.attr("for");
	    l='#'+l;
	    l=l+"_label";
	    $(l).html( "Ok" );    
	    
	},
	submitHandler: function(){
        $.post("controllers/updateEmail.php", 
        { email1: $("#email-input").val(), email2: $("#newemail2-input").val()})
		.done(function(data) {
			alert("Adres email został pomyślnie zaktualizowany")
			window.location.href = "profile.php";
		});
    },
	rules: {
		email1: {
			required: true,
			customEmail: true,
			checkUser: true
		},
		email2: {
			required: true,
			equalTo: "#email-input"
		}
	},
	 messages: {
		email1: {
			required: "Pole email jest puste !",
			customEmail: "Zły format adresu email !",
		},
		email2: {
			required: "Pole email jest puste !",
			equalTo: "Podane adresy email różnią się !"
		}
	}
});

var validate3 = $(".passform").validate({
	errorPlacement: function(error, element) {
	    var l = element.attr("name");
	    l='#'+l+"_label";
	    $(l).html( error );
	  	        
	},
	success: function(label) {		
	    var l = label.attr("for");
	    l='#'+l;
	    l=l+"_label";
	    $(l).html( "Ok" );    
	    
	},
	submitHandler: function(){
        $.post("controllers/updatePassword.php", 
        { pass1: $("#pass-input").val(), pass2: $("#pass2-input").val()})
		.done(function(data) {
			$("#addcart").css("display", "block");
			$("#bg2").css("display", "block");
			$.cookie("rememberme", "", { expires: 0, path: '/' });
			setTimeout(function() {window.location.href = "index.php";}, 5000);
		});
    },
	rules: {
		password1: {
			required: true,
			minlength: 8,
			passW: true
		},
		password2: {
			required: true,
			equalTo: "#pass-input"
		},
	},
	 messages: {
		password1: {
			required: "Pole hasło jest puste !",
			minlength: jQuery.format("Minimum {0} znaków !")
		},
		password2: {
			required: "Pole hasło jest puste !",
			equalTo: "Podane hasła różnią się !"
		}
	}
});
$("#bg2, #ret").click(function(){
	$("#bg2").css("display", "none");
	$(".big2div").css("display", "none");
});
</script>