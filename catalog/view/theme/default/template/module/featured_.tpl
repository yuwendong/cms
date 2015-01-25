<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-case">
      <?php foreach ($cases as $case) { ?>
      <div>
        <?php if ($case['thumb']) { ?>
        <div class="image"><a href="<?php echo $case['href']; ?>"><img src="<?php echo $case['thumb']; ?>" alt="<?php echo $case['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $case['href']; ?>"><?php echo $case['name']; ?></a></div>
        <?php if ($case['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $case['rating']; ?>.png" alt="<?php echo $case['reviews']; ?>" /></div>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
