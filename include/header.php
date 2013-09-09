<a href="cms/index.php"><div>GO TO CMS</div></a>
<div class ="panel-div">
<?php
	if(!session_id())
	{
	    session_start();
	} 
	if(!isset($_SESSION['login'])) {
	echo("<div class='sub-panel-div' id='profile' >
		<p>Witaj.&#160</p> 
		<a href='../asanti/login.php'> <p class='p-profile' id='p-login'>Zaloguj się</p></a>
		<p>&#160lub&#160</p> 
		<a href='../asanti/register.php'><p class='p-profile' id='p-register'>załóż konto.</p></a>
	</div>
	<div class='sub-panel-div' id='profile-icon' ></div>
	");
	
	}elseif(isset($_SESSION['login'])) {
		echo("<div class='sub-panel-div' id='profile' >
		<p>Witaj&#160</p> 
		<a href='profile.php'> <p class='p-profile' id='p-login'>".$_SESSION['name']."&#160".$_SESSION['lastname']."</p></a>
		<p>.&#160</p> 
		<a id='logoutButton'><p class='p-profile' id='p-register'>Wyloguj się</p></a>
		<p>.</p> 
	</div>
	<div class='sub-panel-div' id='profile-icon' ></div>
	");
	}
?>

</div>
<div class="menu">
	                
			<ul class="menu-anim">               
	                    <li class="other" id="asanti"></li>
	                    <li class="other"><a href="contact.html"><div class="menu-div">KONTAKT</div></a></li>                                        
	                    <li class="other"><a href="about.html"><div class="menu-div">O NAS</div></a></li>
	                    <li class="current"><a href="shop.php"><div class="menu-div">SKLEP</div></a></li>                 
			</ul>                
</div>
<script type="text/javascript">
$("#logoutButton").click(function(){
        $.post("controllers/logoutUser.php")
		.done(function(data) {
				window.location.href = "shop.php";
		});
});

$("#asanti").click(function(){
	window.location.href = "shop.php";
});
</script>