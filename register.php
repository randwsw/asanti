<?php if(!session_id()) { session_start();} ?>
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
	<div class="regfiller">
		
	<!-- Include background animation ------------------------------------------- -->
	<!-- <?php include 'include/backanim.php'; ?> -->
	<!-- ------------------------------------------------------------------------ -->
	
		<div class="register-div">
			<img src="img/nextlogo.png" id="log-logo">
					
				<form method="POST" class="regform" id="regform" >
					<h2>Rejestracja</h2>
					<div class ="formdiv">
						<p>Adres email</p>
						<input type='text' class="form-text-input" id="emailinput" name="emailinput"/>
						<div class="errordiv" id="emailinput_label"></div>						
					</div>
					
					<div class ="formdiv">
						<p>Hasło</p>
						<input type='password' class="form-text-input" id="passinput" name="passinput"/>
						<div class="errordiv" id="passinput_label"></div>						
					</div>
										
					<div class ="formdiv">
						<p>Potwierdź hasło</p>
						<input type='password' class="form-text-input" id="pass2input" name="pass2input"/>
						<div class="errordiv" id="pass2input_label"></div>
					</div>
										
					<div class ="formdiv">
						<div class="formdivcolumn">
							<p>Imię</p>
							<input type='text' class="form-text-input-s" id="nameinput" name="nameinput"/>
							<div class="errordiv" id="nameinput_label"></div>
						</div>
						<div class="formdivcolumn" id="lastname">
							<p>Nazwisko</p>
							<input type='text' class="form-text-input-s" id="lastnameinput" name="lastnameinput"/>
							<div class="errordiv" id="lastnameinput_label"></div>
						</div>
					</div>
					
					
					<div class ="formdiv">
						<div class="formdivcolumn" id="divstreet">
							<p>Ulica</p>
							<input type='text' class="form-text-input" id="street" name="street"/>
							<div class="errordiv" id="street_label"></div>
						</div>
						<div class="formdivcolumn" id="divpcode">
							<p>Kod poczt.</p>
							<input type='text' class="form-text-input" id="pcode" name="pcode"/>
							<div class="errordiv" id="pcode_label"></div>
						</div>
					</div>
					
					<div class ="formdiv">
						<p>Miejscowość</p>
						<input type='text' class="form-text-input" id="cityinput" name="cityinput"/>
						<div class="errordiv" id="cityinput_label"></div>
					</div>					
					
					<div class ="formdiv">
						<p>Telefon</p>
						<input type='text' class="form-text-input" id="phoneinput" name="phoneinput"/>
						<div class="errordiv" id="phoneinput_label"></div>
					</div>
										
					<div class ="formdiv">
						<input class="form-button" type="submit" value="Zarejestruj się" id="regButton"/>
						<a onclick="history.back(-1)"><p id="pback">&#171 wróć do sklepu</p></a>
					</div>
					
				</form>
				
			</div>
			<!-- <div class = "needle">
				 <img src="img/needle.png" alt="Smiley face" height="593" width="495"> 
			</div>	 -->					
	</div>
</body>
</html>
<script type="text/javascript">
		$('#emailinput').watermark("Wpisz swój adres email");
		$('#passinput').watermark("Wpisz swoje hasło");
		$('#pass2input').watermark("Ponownie wpisz hasło");
		$('#nameinput').watermark("Wpisz swoje imię");
		$('#lastnameinput').watermark("Wpisz swoje nazwisko");
		$('#street').watermark("Ulica i nr domu / mieszkania");
		$('#pcode').watermark("xx - xxx");
		$('#cityinput').watermark("Wpisz swoje miasto / miejscowość");
		$('#phoneinput').watermark("Wpisz swój numer telefonu");
		
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
	var em = $('#emailinput').val();
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
var validate = $(".regform").validate({
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
        $.post("controllers/addUser.php", 
        { email: $("#emailinput").val(), password1: $("#passinput").val(), password2: $("#pass2input").val(), 
          name: $("#nameinput").val(), lastname: $("#lastnameinput").val(), pcode: $("#pcode").val(), 
          street: $("#street").val(), city: $("#cityinput").val(), phone: $("#phoneinput").val() })
		.done(function(data) {
		alert("Konto zostało utworzone");
		});
    },
	rules: {
		emailinput: {
			required: true,
			customEmail: true,
			checkUser: true
		},
		passinput: {
			required: true,
			minlength: 8,
			passW: true
		},
		pass2input: {
			required: true,
			equalTo: "#passinput"
		},
		nameinput: {
			required: true,
			nameLastname: true		
		},
		lastnameinput: {
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
		cityinput: {
			required: true,
			city: true			
		},
		phoneinput: {
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

$("#regButton").click(function()
{
	
});

</script>