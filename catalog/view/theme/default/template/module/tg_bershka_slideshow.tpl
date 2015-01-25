<div class="slideshow-top"> </div>
<div class="slideshow-middle">
	<div class="tg_bershka_slideshow">
  		<div id="tg_bershka_slideshow<?php echo $module; ?>" class="nivoSlider" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
    		<?php foreach ($banners as $banner) { ?>
    			<?php if ($banner['link']) { ?>
   				 	<a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
    			<?php } else { ?>
    				<img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
    			<?php } ?>
    		<?php } ?>
  		</div>
	</div>
</div>
<div class="slideshow-bottom"> </div>
<script type="text/javascript">
$(document).ready(function() {
	$('#tg_bershka_slideshow<?php echo $module; ?>').nivoSlider({
		animSpeed:'<?php echo $speed; ?>',
		effect:'<?php echo $effect; ?>',
		slices:'<?php echo $slices; ?>',
		boxCols:'<?php echo $boxcolumns; ?>',
        boxRows:'<?php echo $boxrows; ?>',
		directionNavHide:false,
		captionOpacity:0.6,
		pauseTime:<?php echo $delay; ?>,		
		pauseOnHover:<?php echo $pause; ?>

		});
    });
</script>
