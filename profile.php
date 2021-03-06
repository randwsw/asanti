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
		
		require_once("include/config.php");
	   	$conn=mysqli_connect($config["db"]["db1"]["dbhost"], $config["db"]["db1"]["username"], $config["db"]["db1"]["password"], $config["db"]["db1"]["dbname"]);
		mysqli_set_charset($conn, "utf8");
		
		$login = $conn->real_escape_string($_SESSION['login']);
		$login= $purifier->purify($login);
		
		$returnValue = null;


		if (mysqli_connect_errno())
			{
		 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		
		
		
		$result = mysqli_query($conn,"SELECT pValue, email, password, name, lastname, pcode, street, city, hnum, anum FROM users u, address a, phone p WHERE email = '$login' AND p.user_id = u.id AND a.user_id = u.id");
				while($e = mysqli_fetch_array($result))
				  {
						$phone = $e['pValue'];
						$name= $e['name'];
						$lastname = $e['lastname'];
						$pcode = $e['pcode'];
						$street = $e['street'];
						$city = $e['city'];
						$password = $e['password'];
						$hnum = $e['hnum'];
						$anum = $e['anum'];
				  }
		// echo($name." ".$lastname);
		mysqli_close($conn);
	} 
	if($anum==0) {
		$anum='';
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
						<div class='formdivcolumn' id='divstrt'>
							<p>Ulica</p>
							<input type='text' class='form-text-input' name='street' id='street' value='".$street."'/>
							<div class='errordiv' id='street_label'></div>				
						</div>
						<div class='formdivcolumn' id='divnbr2'>
							<p>Nr domu</p>
							<input type='text' class='form-text-input' id='pnrh' name='pnrh' value='".$hnum."'/>
							<div class='errordiv' id='pnrh_label'></div>
						</div>
						<div class='formdivcolumn' id='divnbr1'>
							<p>Nr mieszk.</p>
							<input type='text' class='form-text-input' id='pnra' name='pnra' value='".$anum."'/>
							<div class='errordiv' id='pnra_label'></div>
						</div>				
					</div>
					
					<div class ='formdiv'>
						<div class='formdivcolumn' id='citydiv'>
							<p>Miejscowość</p>
							<input type='text' class='form-text-input' id='cityinput' name='cityinput' value='".$city."'/>
							<div class='errordiv' id='cityinput_label'></div>
						</div>
						<div class='formdivcolumn' id='divpcode'>
							<p>Kod poczt.</p>
							<input type='text' class='form-text-input' id='pcode' name='pcode' value='".$pcode."'/>
							<div class='errordiv' id='pcode_label'></div>
						</div>
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
		$('#street').watermark("Wpisz nazwę ulicy");
		$('#pcode').watermark("xx - xxx");
		$('#cityinput').watermark("Wpisz swoje miasto");
		$('#phone-input').watermark("Wpisz swój numer telefonu");
		$('#pnrh').watermark("np. 7a");
		$('#pnra').watermark("np. 13");
		
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
    
    jQuery.validator.addMethod("street", function(value, element) {
        return this.optional(element) || value.match(/^[A-ZŻŹĆĄŚĘŁÓŃa-zżźćńółęąś0-9 ]+$/);
    }, "Tylko litery i cyfry !")
    
    jQuery.validator.addMethod("city", function(value, element) {
        return this.optional(element) || value.match(/^[A-ZŻŹĆĄŚĘŁÓŃ][a-zżźćńółęąś]+(?:[\s-][A-ZŻŹĆĄŚĘŁÓŃ][a-zżźćńółęąś]+)*$/);
    }, "Nieprawidłowa nazwa miejscowości !")
    
    jQuery.validator.addMethod("passW", function(value, element) {
        return this.optional(element) || value.match(/^((?=.*\d)(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[^A-Za-z0-9])(?=.*[a-z])|(?=.*[^A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z])|(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9]))^.*$/);
    }, "Musi zawierać małą i wielką literę oraz cyfrę, lub znak spec. !")
    
    jQuery.validator.addMethod("phone", function(value, element) {
        return this.optional(element) || value.match(/^([0-9]{9})|(([0-9]{3} ){2}[0-9]{3})|(([0-9]{2} )[0-9]{7})$/);
    }, "Tylko cyfry (9) i/lub znak spacji !")
    
    jQuery.validator.addMethod("numalph", function(value, element) {
        return this.optional(element) || value.match(/^[a-zA-Z0-9]+$/);
    }, "Cyfry i Num. !")

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
          street: $("#street").val(), city: $("#cityinput").val(), phone: $("#phone-input").val(), pnrh: $("#pnrh").val(), pnra: $("#pnra").val() })
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
			required: true,
			street: true
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
		},
		pnra: {
			number: true
		},
		pnrh: {
			required: true,
			numalph: true
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
			required: "Pole ulica jest puste !",
					
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
		},
		pnra: {
			required: "Puste pole !",
			number: "Tylko cyfry !"
		},
		pnrh: {
			required: "Puste pole !",
			numalph: "Bez zn.spec. !"
			
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