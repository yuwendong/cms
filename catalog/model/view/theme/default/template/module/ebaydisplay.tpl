<div class="box">
    <div class="box-heading"><?php echo $heading_title; ?></div>
    <div class="box-content">
        <div class="box-case">
            <?php foreach ($cases as $case) { ?>
            <div>
                <?php if ($case['thumb']) { ?>
                <div class="image"><a href="<?php echo $case['href']; ?>" target="_blank"><img src="<?php echo $case['thumb']; ?>" alt="<?php echo $case['name']; ?>" /></a></div>
                <?php } ?>
                <div class="name"><a href="<?php echo $case['href']; ?>" target="_blank"><?php echo $case['name']; ?></a></div>
                <div class="price">
                    <?php echo $case['price']; ?>
                </div>
            </div>
            <?php } ?>
            <img src="<?php echo $tracking_pixel; ?>" height="0" width="0" />
        </div>
    </div>
</div>
