<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($cases) { ?>
  <table class="compare-info">
    <thead>
      <tr>
        <td class="compare-case" colspan="<?php echo count($cases) + 1; ?>"><?php echo $text_case; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="100"><?php echo $text_name; ?></td>
        <?php foreach ($cases as $case) { ?>
        <td class="name"><a href="<?php echo $cases[$case['case_id']]['href']; ?>" style="color: rgb(0%, 0%, 100%) ;"><?php echo $cases[$case['case_id']]['name']; ?></a></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_model; ?></td>
        <?php foreach ($cases as $case) { ?>
        <td><?php echo $cases[$case['case_id']]['model']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_tag; ?></td>
        <?php foreach ($cases as $case) { ?>
        <td>
		  <?php $tags=$cases[$case['case_id']]['tag']; ?>	
		  <?php if ($tags) { ?>
			<?php for ($i = 0; $i < count($tags); $i++) { ?>			
			<?php $flag = 0; ?>
			<?php foreach ($cases as $case_compare) { ?>
				<?php $compare_tags=$cases[$case_compare['case_id']]['tag']; ?>	
				<?php if ($compare_tags) { ?>
				<?php for ($c_i = 0; $c_i < count($compare_tags); $c_i++) { ?>
					<?php if ($compare_tags[$c_i]['tag']==$tags[$i]['tag']) { ?>
						<?php $flag++; ?>
					<?php } ?>
				<?php } ?>
				<?php } ?>
			<?php } ?>	
			<?php $red = (100*($flag-1))/(count($cases)-1); ?>
			<?php if(count($cases)==1){ $red = 0;} ?>					
			<?php if ($i < (count($tags) - 1)) { ?>
			<a href="<?php echo $tags[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $tags[$i]['tag']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $tags[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $tags[$i]['tag']; ?></a>
			<?php } ?>
			<?php } ?>
		  <?php } ?>
		</td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_figure; ?></td>
        <?php foreach ($cases as $case) { ?>
        <td>
		  <?php $figures=$cases[$case['case_id']]['figure']; ?>	
		  <?php if ($figures) { ?>
			<?php for ($i = 0; $i < count($figures); $i++) { ?>			
			<?php $flag = 0; ?>
			<?php foreach ($cases as $case_compare) { ?>
				<?php $compare_figures=$cases[$case_compare['case_id']]['figure']; ?>	
				<?php if ($compare_figures) { ?>
				<?php for ($c_i = 0; $c_i < count($compare_figures); $c_i++) { ?>
					<?php if ($compare_figures[$c_i]['figure']==$figures[$i]['figure']) { ?>
						<?php $flag++; ?>
					<?php } ?>
				<?php } ?>
				<?php } ?>
			<?php } ?>	
			<?php $red = (100*($flag-1))/(count($cases)-1); ?>	
			<?php if(count($cases)==1){ $red = 0;} ?>						
			<?php if ($i < (count($figures) - 1)) { ?>
			<a href="<?php echo $figures[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $figures[$i]['figure']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $figures[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $figures[$i]['figure']; ?></a>
			<?php } ?>
			<?php } ?>
		  <?php } ?>
		</td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_spread; ?></td>
        <?php foreach ($cases as $case) { ?>
        <td>
		  <?php $spreads=$cases[$case['case_id']]['spread']; ?>	
		  <?php if ($spreads) { ?>
			<?php for ($i = 0; $i < count($spreads); $i++) { ?>			
			<?php $flag = 0; ?>
			<?php foreach ($cases as $case_compare) { ?>
				<?php $compare_spreads=$cases[$case_compare['case_id']]['spread']; ?>	
				<?php if ($compare_spreads) { ?>
				<?php for ($c_i = 0; $c_i < count($compare_spreads); $c_i++) { ?>
					<?php if ($compare_spreads[$c_i]['spread']==$spreads[$i]['spread']) { ?>
						<?php $flag++; ?>
					<?php } ?>
				<?php } ?>
				<?php } ?>
			<?php } ?>	
			<?php $red = (100*($flag-1))/(count($cases)-1); ?>		
			<?php if(count($cases)==1){ $red = 0;} ?>					
			<?php if ($i < (count($spreads) - 1)) { ?>
			<a href="<?php echo $spreads[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $spreads[$i]['spread']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $spreads[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $spreads[$i]['spread']; ?></a>
			<?php } ?>
			<?php } ?>
		  <?php } ?>
		</td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_time; ?></td>
        <?php foreach ($cases as $case) { ?>
        <td>
		  <?php $times=$cases[$case['case_id']]['time']; ?>	
		  <?php if ($times) { ?>
			<?php for ($i = 0; $i < count($times); $i++) { ?>			
			<?php $flag = 0; ?>
			<?php foreach ($cases as $case_compare) { ?>
				<?php $compare_times=$cases[$case_compare['case_id']]['time']; ?>	
				<?php if ($compare_times) { ?>
				<?php for ($c_i = 0; $c_i < count($compare_times); $c_i++) { ?>
					<?php if ($compare_times[$c_i]['time']==$times[$i]['time']) { ?>
						<?php $flag++; ?>
					<?php } ?>
				<?php } ?>
				<?php } ?>
			<?php } ?>	
			<?php $red = (100*($flag-1))/(count($cases)-1); ?>		
			<?php if(count($cases)==1){ $red = 0;} ?>					
			<?php if ($i < (count($times) - 1)) { ?>
			<a href="<?php echo $times[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $times[$i]['time']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $times[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $times[$i]['time']; ?></a>
			<?php } ?>
			<?php } ?>
		  <?php } ?>
		</td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_location; ?></td>
        <?php foreach ($cases as $case) { ?>
        <td>
		  <?php $locations=$cases[$case['case_id']]['location']; ?>	
		  <?php if ($locations) { ?>
			<?php for ($i = 0; $i < count($locations); $i++) { ?>			
			<?php $flag = 0; ?>
			<?php foreach ($cases as $case_compare) { ?>
				<?php $compare_locations=$cases[$case_compare['case_id']]['location']; ?>	
				<?php if ($compare_locations) { ?>
				<?php for ($c_i = 0; $c_i < count($compare_locations); $c_i++) { ?>
					<?php if ($compare_locations[$c_i]['location']==$locations[$i]['location']) { ?>
						<?php $flag++; ?>
					<?php } ?>
				<?php } ?>
				<?php } ?>
			<?php } ?>	
			<?php $red = (100*($flag-1))/(count($cases)-1); ?>	
			<?php if(count($cases)==1){ $red = 0;} ?>						
			<?php if ($i < (count($locations) - 1)) { ?>
			<a href="<?php echo $locations[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $locations[$i]['location']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $locations[$i]['href']; ?>" style="color: rgb(<?php echo $red; ?>%, 0%, <?php echo 100-$red; ?>%) ;"><?php echo $locations[$i]['location']; ?></a>
			<?php } ?>
			<?php } ?>
		  <?php } ?>
		</td>
        <?php } ?>
      </tr>
    </tbody>
    <tr>
      <td></td>
      <?php foreach ($cases as $case) { ?>
      <td class="remove"><a href="<?php echo $case['remove']; ?>" class="button"><?php echo $button_remove; ?></a></td>
      <?php } ?>
    </tr>
  </table>
  <div class="comment">
    <?php echo $text_comment; ?>
  </div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>