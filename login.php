<?php if(!session_id()) { session_start();} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	
    <link rel="stylesheet" href="css/borders/loginborders.css" />
    
</head>

<body>
	<!-- Include background animation ------------------------------------------- -->
	<?php include 'include/backanim.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<div class="bg"></div> 
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
	<div class="logfiller">
		
	<!-- Include background animation ------------------------------------------- -->
	<!-- <?php include 'include/backanim.php'; ?> -->
	<!-- ------------------------------------------------------------------------ -->
		<a href='index.php'><img src='img/nextlogo.png' id='log-logo'></a>
		<div class="logreg-div">
			
			<div class="log-div">
					
					<form method="POST" class="logform" id="logform">
					
					<h2>Zaloguj się</h2>
					<div class ="formdiv">
						<p>Masz już konto? Wpisz swoje dane.</p>
					</div>
					<div class ="formdiv">
						<p>Adres email</p>
						<input type='text' class="form-text-input" id="loginInput" name="loginInput"/>
						<div class="errordiv" id="loginInput_label"></div>
					</div>
					<div class ="formdiv">
						<p>Hasło</p>
						<input type='password' class="form-text-input" id="passwordInput" name="passwordInput"/>
						<div class="errordiv" id="passwordInput_label"></div>
					</div>
					<div class ="formdiv">
						<div class ="formdivcolumn">
							<input class="form-button" type="submit" value="Zaloguj się" />
						</div>
						<div class ="formdivcolumn" id="logpcheckbox">
							<p> Zapamiętaj mnie</p>
						</div>
						<div class ="formdivcolumn" id="logcheckbox">
							<input type="checkbox" name="rememberMe" id="rememberMe" ></input>
						</div>
					</div>
					<div class ="formdiv">
						<p>Nie pamiętasz swojego hasła?</p> <a href="resetPassword.php"><p>Kliknij tutaj.</p></a>
					</div>
					
					</form>
					
			</div>
			
			<div class ="reg-div">
				<h2>Zarejestruj się</h2>
				<div class ="formdiv">
					<p>Nie masz konta? Kliknij poniżej.</p>
				</div>
				<!-- <div class ="formdiv">
					<ul>
			            <li>&#187 Costam1</li>
			            <li>&#187 Costam2</li>
			            <li>&#187 Costam3</li>
			            <li>&#187 Costam4</li>
			            <li>&#187 Costam5</li>
			        </ul>
			    </div> -->
			    <br>	
				<div class ="formdiv">
					<a href="../asanti/register.php"><input class="form-button" type="button" value="Utwórz konto" /></a>
				</div>
				<div class ="formdiv" id="backdiv">
					<a href="index.php"><p id="pback">&#171 wróć do sklepu</p></a>
				</div>
			</div>
		</div>
	</div>
	<div class="big2div" id="addcart">
				<div class="row2div" id="top2div">
				</div>
				<div class="row2div" id="mid2div">
					<div class="right2div" id="midright2div">
						
					</div>
					<div class="center2div" id="midcenter2div">
						<p id="main">Nieprawidłowy login, hasło lub nieaktywne konto.</p>
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

$.urlParam = function(name){
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}

		$('#loginInput').watermark("Wpisz tutaj swój adres email");
		$('#passwordInput').watermark("Wpisz tutaj swoje hasło");
		
	jQuery.validator.addMethod("customEmail", function(value, element) {
        return this.optional(element) || value.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
    }, "Zły format adresu email!")
		
var validate = $(".logform").validate({
	errorPlacement: function(error, element) {
	    var l = element.attr("name");
	    l='#'+l+"_label";
	    $(l).html( error );
	        
	},
	success: function(label) {
		
	    // var l = label.attr("for");
	    // l='#'+l;
	    // l=l+"_label";
	    // $(l).html( "Ok" );    
	    
	},
	submitHandler: function(){
        $.post("controllers/logUser.php", 
        { email: $("#loginInput").val(), password1: $("#passwordInput").val(), rememberMe: $("#rememberMe").prop('checked') })
		.done(function(data) {
			if(data!='')
			{
				$(".big2div").css("display", "block");
				$("#bg2").css("display", "block");
			}else {
				if ($('#rememberMe').prop('checked') == true) {
					$.cookie("rememberme", $("#loginInput").val(), { expires: 365, path: '/' });
				}
				
				
				cr = $.urlParam('cr');
				if(cr==1)
				{ 
					window.location.href = "cart.php";		
				}
				else {
					window.location.href = "index.php";		
				}		
			}
		});
    },
	rules: {
		loginInput: {
			required: true,
			customEmail: true
		},
		passwordInput: {
			required: true,
			minlength: 8
		}
	},
	 messages: {
		loginInput: {
			required: "Pole email jest puste !"
		},
		passwordInput: {
			required: "Pole hasło jest puste !",
			minlength: jQuery.format("Minimum {0} znaków !")
		}
	}
});
$("#bg2, #ret").click(function(){
	$("#bg2").css("display", "none");
	$(".big2div").css("display", "none");
});
</script>