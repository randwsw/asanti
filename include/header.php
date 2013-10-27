<?php if(!session_id()) { session_start();} ?>
<!-- <div class="menu"> -->
	
	        <div id="background">
				<div class ="panel-div">
					<!-- <a style='float: right'href="cms/index.php"><div>GO TO CMS</div></a> -->
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
<div class="n-menu">
	<p><a href = "payments.php" class="n-item">Płatności</a></p>
	<p><a href = "about.php" class="n-item">O Nas</a></p>
	<p><a href = "gallery.php" class="n-item">Galeria</a></p>
	<p><a href = "promo.php" class="n-item">Promocje</a></p>
	<p><a href = "index.php" class="n-item">Sklep</a></p>
</div>
<!-- <ul class="menu-anim">               
	                    <li class="other"><a href="contact.html"><div class="menu-div">KONTAKT</div></a></li>                                        
	                    <li class="other"><a href="about.html"><div class="menu-div">O NAS</div></a></li>
	                    <li class="current"><a href="index.php"><div class="menu-div">SKLEP</div></a></li>                 
			</ul>   -->  
			</div>   
			<!-- <ul class="menu-anim">               
	                    <li class="other"><a href="contact.html"><div class="menu-div">KONTAKT</div></a></li>                                        
	                    <li class="other"><a href="about.html"><div class="menu-div">O NAS</div></a></li>
	                    <li class="current"><a href="index.php"><div class="menu-div">SKLEP</div></a></li>                 
			</ul>   -->         
<!-- </div> -->
<script type="text/javascript">
$("#logoutButton").click(function(){
        $.post("controllers/logoutUser.php")
		.done(function(data) {
				$.cookie("rememberme", "", { expires: 0, path: '/' });
				window.location.href = "index.php";
		});
});

$("#asanti").click(function(){
	window.location.href = "index.php";
});
</script>