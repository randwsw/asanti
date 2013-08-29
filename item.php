<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asanti - sklep</title>

	<!-- Include links ---------------------------------------------------------- -->
	<?php include 'include/links.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
    
    
    
    
    
    
<?php

if(!session_id())
	{
	    session_start();
	} 


// Vars /////////////////////////////////////////////////////////////////////////////////////////////// //
$itemId = $_GET['id'];
$photosList = array();
$conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");



// class item {
	// public $id;
	// public $name;
	// public $description;
	// public $headPhotoUrl;
// }
// 
// 
// $itemList = array();
// //////////////////////////////////////////////////////////////////////////////////////////////////// //







// Get item /////////////////////////////////////////////////////////////////////////////////////////// //
if (mysqli_connect_errno())
	{
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}


// CHECK FOR UNIQUE ITEM ////////////////////////////
$result1 = mysqli_query($conn,"SELECT COUNT(*) AS count FROM item WHERE id = '$itemId'");
while($row1 = mysqli_fetch_array($result1))
{
	if($row1['count'] != 1){
		header('Location: shop.php');
	}else{
		// GET ITEM ROWS ///////////////////////////////////
		$result2 = mysqli_query($conn,"SELECT * FROM item WHERE id = '$itemId'");
	
		while($row2 = mysqli_fetch_array($result2))
			{
				$name = $row2['name'];
				$description = $row2['description'];
				$headPhotoId = $row2['headPhotoId'];
			}
			// GET HEAD PHOTO ////////////////////////////////////
			$result3 = mysqli_query($conn,"SELECT * FROM photo WHERE id = '$headPhotoId'");
			
			while($row3 = mysqli_fetch_array($result3))
			{
				$headPhotoUrl = $row3['url'];
			}
			
			$result4 = mysqli_query($conn,"SELECT * FROM photo WHERE item_id = '$itemId' AND isHeadPhoto = '0'");
			
			while($row4 = mysqli_fetch_array($result4))
			{
				$photo = $row4['url'];
				array_push($photosList, $photo);
			}
			
			mysqli_close($conn);
	}
}



// //////////////////////////////////////////////////////////////////////////////////////////////////// //
	
	
	
	
	
// Output ///////////////////////////////////////////////////////////////////////////////////////////// //
	
	// echo ('<select id="selectClasses">
			// <option value="0">Wszystkie zajęcia</option>');
	// foreach($itemList as $item) {
		// echo ('<option value="' . $classes->id . '">' . $classes->name . '</option>');
	// }
	// echo ('</select>');
	
// //////////////////////////////////////////////////////////////////////////////////////////////////// //
	


?>
    
    
    
<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.bigPhotoContent').css('display', 'block');

				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 0.67;
				$('#thumbs ul.thumbs li, div.navigation a.pageLink').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,
					fadeSpeed:         'fast',
					exemptionSelector: '.selected'
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                     2500,
					numThumbs:                 20,
					preloadAhead:              10,
					enableTopPager:            false,
					enableBottomPager:         false,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          false,
					renderNavControls:         false,
					playLinkText:              '',
					pauseLinkText:             '',
					prevLinkText:              '',
					nextLinkText:              '',
					nextPageLinkText:          '',
					prevPageLinkText:          '',
					enableHistory:             true,
					autoStart:                 false,
					syncTransitions:           true,
					defaultTransitionDuration: 500,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);

						// Update the photo index display
						this.$captionContainer.find('div.photo-index')
							.html('Photo '+ (nextIndex+1) +' of '+ this.data.length);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						var prevPageLink = this.find('a.prev').css('visibility', 'hidden');
						var nextPageLink = this.find('a.next').css('visibility', 'hidden');
						
						// Show appropriate next / prev page links
						if (this.displayedPage > 0)
							prevPageLink.css('visibility', 'visible');

						var lastPage = this.getNumPages() - 1;
						if (this.displayedPage < lastPage)
							nextPageLink.css('visibility', 'visible');

						this.fadeTo('fast', 1.0);
					}
				});

				/**************** Event handlers for custom next / prev page links **********************/

				gallery.find('a.prev').click(function(e) {
					gallery.previousPage();
					e.preventDefault();
				});

				gallery.find('a.next').click(function(e) {
					gallery.nextPage();
					e.preventDefault();
				});

				/****************************************************************************************/

				/**** Functions to support integration of galleriffic with the jquery.history plugin ****/

				// PageLoad function
				// This function is called when:
				// 1. after calling $.historyInit();
				// 2. after calling $.historyLoad();
				// 3. after pushing "Go Back" button of a browser
				function pageload(hash) {
					// alert("pageload: " + hash);
					// hash doesn't contain the first # character.
					if(hash) {
						$.galleriffic.gotoImage(hash);
					} else {
						gallery.gotoIndex(0);
					}
				}

				// Initialize history plugin.
				// The callback is called at once by present location.hash. 
				$.historyInit(pageload, "advanced.html");

				// set onlick event for buttons using the jQuery 1.3 live method
				$("a[rel='history']").live('click', function(e) {
					if (e.button != 0) return true;

					var hash = this.href;
					hash = hash.replace(/^.*#/, '');

					// moves to a new page. 
					// pageload is called at once. 
					// hash don't contain "#", "?"
					$.historyLoad(hash);

					return false;
				});

				/****************************************************************************************/
			});
		</script>
    
    
    
    
</head>

<body>

    <div class="container">
    	
	<!-- Include header --------------------------------------------------------- -->
	<?php include 'include/header.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	<!-- Include submenu -------------------------------------------------------- -->
	<?php include 'include/submenu.php'; ?>
	<!-- ------------------------------------------------------------------------ -->
	
	
	<div id="itemContainer">
		<div id="itemPhotoContainer">
			<div class="bigPhotoContent">
					<div class="slideshow-container">
						<div id="controls" class="controls"></div>
						<div id="loading" class="loader"></div>
						<div id="slideshow" class="slideshow"></div>
					</div>
					<!-- <div id="caption" class="caption-container">
						<div class="photo-index"></div>
					</div> -->
				</div>
			<!-- <img id="itemBigPhoto" src="<?php echo("$headPhotoUrl"); ?>"/> -->
				<!-- Start Advanced Gallery Html Containers -->				
				<div class="navigation-container">
					<div id="thumbs" class="navigation">
						<a class="pageLink prev" style="visibility: hidden;" href="#" title="Previous Page"></a>
					
						<ul class="thumbs noscript">
							<!-- <li>
								<a class="thumb" name="leaf" href="<?php echo("$headPhotoUrl"); ?>" title="Asanti for kids">
									<div class='frame'><span class='helper'></span>
										<img src="<?php echo("$headPhotoUrl"); ?>" />
									</div>
								</a>
								<div class="caption">
									<div class="image-title">Title #0</div>
									<div class="image-desc">Description</div>
									<div class="download">
										<a href="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015_b.jpg">Download Original</a>
									</div>
								</div> 
							</li> -->
							<?php
								foreach($photosList as $photo)
								{
									echo("<li>
												<a class='thumb' href='$photo' title='Asanti for kids' >
													<div class='frame'><span class='helper'></span>
														<img src='$photo' />
												</div>
												</a>
										</li>");
								}
							?>
						</ul>
						<a class="pageLink next" style="visibility: hidden;" href="#" title="Next Page"></a>
					</div>
				</div>
				<!-- End Gallery Html Containers -->
				<div style="clear: both;"></div>
		</div>
		<div id="itemDescriptionContainer">
			<h2 id="itemTitle"><?php echo("$name"); ?><h2>
			<div id="itemDescription"><?php echo("$description"); ?></div>
		</div>
	</div>
	 </div>
   
</body>      
</html>
<script type="text/javascript">
	
$( document ).ready(function() {


	$(function() {			
	    $(".menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
	    
	    $(".sub-menu-anim").lavaLamp({
	        fx: "backout",
	        speed: 700,
	    });
			
		// var $tlt = jQuery.noConflict();
		// $tlt(function() {
		// $tlt('.lavamenu').lavalamp();
		// });
	});
	
	
});

$('.imageContainer').mouseenter(function() {
   $(this).children('.imageOverlay').fadeIn(100, function() {
   		$(this).children('.imageOverlay').stop();
   });
});

$('.imageContainer').mouseleave(function() {
	$(this).children('.imageOverlay').fadeOut(100, function() {
		$(this).stop();
});
});


</script>

