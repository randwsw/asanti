<?php if(!session_id()) { session_start();} ?>
<?php if(isset($_SESSION['login'])){
	header('location: index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aktywacja konta</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    <link rel="stylesheet" href="css/borders/resetpwborders.css" />
</head>
<body>
	<!-- Include background animation ------------------------------------------- -->
	<?php include 'include/backanim.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<div class="bg"></div>
	<div class="bigdiv">
		<div class="rowdiv" id="topdiv">
		</div>
		<div class="rowdiv" id="middiv">
			<div class="rightdiv" id="midrightdiv">
				
			</div>
			<form method="POST" class="resform" id="resform" >
			<div class="centerdiv" id="midcenterdiv">
				<div class ="formdiv">
						<p>Wpisz swój adres email</p>
						<input type='text' class="form-text-input" id="loginInput" name="loginInput"/>
						<input class="form-button" type="submit" value="Resetuj hasło" />
						<div class="errordiv" id="loginInput_label"></div>
				</div>
				<div class ='formdiv'>
							<a href='index.php'><p id='pback'>&#171 wróć do sklepu</p></a>
				</div>
				
			</div>
			</form>
			<div class="leftdiv" id="midleftdiv">
				
			</div>
		</div>
		<div class="rowdiv" id="botdiv">
		</div>
	</div>
</body>
<script type="text/javascript">
$('#loginInput').watermark("Wpisz swój adres email");

jQuery.validator.addMethod("checkUser", function(value, element) {
	var em = $('#loginInput').val();
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
  return this.optional(element) || value == user;
}, "Ten adres email nie istnieje!");

jQuery.validator.addMethod("customEmail", function(value, element) {
        return this.optional(element) || value.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
    }, "Zły format adresu email!")

var validate = $(".resform").validate({
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
       $.post("controllers/resetpw.php", 
        { email: $("#loginInput").val() })
		.done(function(data) {
		$("#loginInput_label").html( "Hasło zostało zrestetowane. Nowe hasło zostało wysłane na podany adres email." );
		$('#loginInput').val('');    
		});
    },
	rules: {
		loginInput: {
			required: true,
			customEmail: true,
			checkUser: true
		}
	},
	 messages: {
		loginInput: {
			required: "Pole email jest puste !",
			customEmail: "Zły format adresu email !",
		}
	}
});
</script>