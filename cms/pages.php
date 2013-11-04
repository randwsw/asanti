<?php 
if(!session_id()){
	session_start();
} 
if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {

}else{
	header("Location: login.php");					
}
?>

<!-- 
	automatyczne generowanie optionów w select dla rozmiarów
 -->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asanti - cms</title>
	<link rel="stylesheet" href="../css/cms2.css" type="text/css" />
	<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
	<?php include "../include/links.php"; ?>


  
<script type="text/javascript">
		
		function getURLParameter(name) {
		    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
		}
	

		tinymce.init({
			mode: 'textarea',
			selector:'textarea',
			height: 400,
			width: 1000,
			resize: false,
			plugins: [
                "advlist autolink lists link image"
            ],
			toolbar: "newdocument | bold | italic | underline | strikethrough | alignleft | aligncenter | alignright | alignjustify | styleselect | formatselect | fontselect | fontsizeselect | bullist | numlist | outdent | indent | blockquote | undo redo | removeformat | subscript | superscript",
			content_css: '../css/fonts.css,../css/content.css',
			// font_size : "8pt,10pt,12pt,14pt,16pt,18pt,20pt,24pt,32pt,36pt",
			font_formats: "Andale Mono=andale mono,times;"+
	        "Arial=arial,helvetica,sans-serif;"+
	        "Arial Black=arial black,avant garde;"+
	        "Book Antiqua=book antiqua,palatino;"+
	        "Courier New=courier new,courier;"+
	        "Gabriola=Gabriola;"+
	        "Georgia=georgia,palatino;"+
	        "Helvetica=helvetica;"+
	        "Impact=impact,chicago;"+
	        "OpenSans=OpenSansRegular;"+
	        "Tahoma=tahoma,arial,helvetica,sans-serif;"+
	        "Terminal=terminal,monaco;"+
	        "Times New Roman=times new roman,times;"+
	        "Trebuchet MS=trebuchet ms,geneva;"+
	        "Verdana=verdana,geneva;"+
	        "Webdings=webdings;"+
	        "Wingdings=wingdings,zapf dingbats",
		});
	
	
	function update(){
		$("input[name='saveChanges']").click(function(){
			var page = getURLParameter("page");
			var content = tinyMCE.activeEditor.getContent();
			$.ajax({ 
			    type: 'POST', 
			    url: 'controllers/pagesController.php', 
			    data: {action : "update", page : page, content : content},
			    timeout: 50000,
			    beforeSend: function(){
			    	// $("#progress").show();
			    },
			    complete: function(){
			    	// $("#progress").hide();
			    },
			    error: function (data) {
			    	alert("ajaxError");
			    },
			    success: function (data) {
					$("#confirmAlert").fadeIn("fast");
					$("#confirmAlert").delay(800).fadeOut(800);
				},
			})
		});
	}
	
	$(document).ready(function(){
		$("#confirmAlert").hide();
		update();
	});
</script>

  
</head>


<?php
	$page = $_GET['page'];
	
	switch ($page){
		case "about":
			$title = "Strony: O nas";
			$label = "Treść";
			break;
		case "background":
			$title = "Tło";
			$label = "Zdjęcia";
			break;
		case "payments":
			$title = "Strony: Płatności";
			$label = "Treść";
			break;
	}

	$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
	
	$sql = "SET NAMES 'utf8'";
	!mysqli_query($conn,$sql);
										
										
	if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
												
	$result = mysqli_query($conn,"SELECT * FROM pages WHERE name = '$page'");
												
	while($row1 = mysqli_fetch_array($result))
		{
			$content = $row1['content'];
		}
												
														
	mysqli_close($conn);
?>

<body>
	
	<div id="siteContainer">
		
		<a href="../index.php"><div>GO TO SHOP</div></a>
		
		<div id="header">
			<div id="title">ASANTI CMS</div>
			<div id="subTitle"><?php echo($title); ?></div>
		</div>
		
		<div id="container">
			
			<div id="leftMenu">
				<!-- Include links ---------------------------------------------------------- -->
				<?php include 'include/leftMenu.php'; ?>
				<!-- ------------------------------------------------------------------------ -->
			</div>
			
			<div id="rightContent">
				
				<div id="container">
					<div id="confirmAlert">Pomyślnie zapisano zmiany</div>
					<div id="pages">
						<div class="label"><?php echo($label); ?></div>
		                	<?php if($page != "background"){ ?>
		                		<p> 
		                			<textarea name="content" id="txt_<?php echo($page); ?>" cols="50" rows="15"><?php echo($content); ?></textarea>
		                		</p>
		                		<input type="button" name="saveChanges" value="Zapisz zmiany" />
		                	<?php } ?>
		                	
		                	<?php if($page == "background"){ ?>
		                		BGRND
		                	<?php } ?>
					</div>
				</div>	
							
			</div>
			
		</div>	
		
		
		<div id="footer">ASANTI CMS FOOTER</div>
		
	</div>
	
</body>
	
	
	
	
	
	
	
	
	
		
</html>
