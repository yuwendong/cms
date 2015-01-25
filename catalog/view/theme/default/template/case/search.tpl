<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="content">
    <p><?php echo $entry_search; ?>
      <?php if ($search) { ?>
      <input type="text" name="search" value="<?php echo $search; ?>" />
      <?php } else { ?>
      <input type="text" name="search" value="<?php echo $search; ?>"/>
      <?php } ?>
      <?php echo $text_search_category; ?>
	  <select name="category_id">
        <option value="0"><?php echo $text_category; ?></option>
        <?php foreach ($categories as $category_1) { ?>
        <?php if ($category_1['category_id'] == $category_id) { ?>
        <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
        <?php } ?>
        <?php foreach ($category_1['children'] as $category_2) { ?>
        <?php if ($category_2['category_id'] == $category_id) { ?>
        <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
        <?php } ?>
        <?php foreach ($category_2['children'] as $category_3) { ?>
        <?php if ($category_3['category_id'] == $category_id) { ?>
        <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </select>
      <?php if ($sub_category) { ?>
      <input type="checkbox" name="sub_category" value="1" id="sub_category" checked="checked" />
      <?php } else { ?>
      <input type="checkbox" name="sub_category" value="1" id="sub_category" />
      <?php } ?>
      <label for="sub_category"><?php echo $text_sub_category; ?></label>
	  <?php if ($description) { ?>
		<input type="checkbox" name="description" value="1" id="description" checked="checked" />
		<?php } else { ?>
		<input type="checkbox" name="description" value="1" id="description" />
		<?php } ?>
		<label for="description"><?php echo $entry_description; ?></label>
    <br><br>
	  <?php echo $text_tag; ?>
      <?php if ($tag) { ?>
      <input type="text" name="tag" value="<?php echo $tag; ?>" />
      <?php } else { ?>
      <input type="text" name="tag" value="<?php echo $tag; ?>"/>
      <?php } ?>
	  <?php echo $text_figure; ?>
      <?php if ($figure) { ?>
      <input type="text" name="figure" value="<?php echo $figure; ?>" />
      <?php } else { ?>
      <input type="text" name="figure" value="<?php echo $figure; ?>"/>
      <?php } ?>
	  <?php echo $text_spread; ?>
      <?php if ($spread) { ?>
      <input type="text" name="spread" value="<?php echo $spread; ?>" />
      <?php } else { ?>
      <input type="text" name="spread" value="<?php echo $spread; ?>"/>
      <?php } ?>
	<br><br>
	  <?php echo $text_time; ?>
      <?php if ($time) { ?>
      <input type="text" name="time" value="<?php echo $time; ?>" />
      <?php } else { ?>
      <input type="text" name="time" value="<?php echo $time; ?>"/>
      <?php } ?>
	  <?php echo $text_location; ?>
      <?php if ($location) { ?>
      <input type="text" name="location" value="<?php echo $location; ?>" />
      <?php } else { ?>
      <input type="text" name="location" value="<?php echo $location; ?>"/>
      <?php } ?>
    </p>
	    <div class="right" style="float:right;width:7%"><input type="button" value="<?php echo $button_search; ?>" id="button-search" class="button" /></div>
  </div>
  <h2><?php echo $text_search; ?></h2>
  <?php if ($cases) { ?>
  <div class="case-filter">
    <div class="limit"><?php echo $text_limit; ?>
      <select onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort"><?php echo $text_sort; ?>
      <select onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="case-compare"></div>
  <div class="case-list">
    <?php foreach ($cases as $case) { ?>
    <div>
      <?php if ($case['thumb']) { ?>
      <div class="image"><a href="<?php echo $case['href']; ?>"><img src="<?php echo $case['thumb']; ?>" title="<?php echo $case['name']; ?>" alt="<?php echo $case['name']; ?>" /></a></div>
      <?php } ?>
      <div class="name"><a href="<?php echo $case['href']; ?>"><?php echo $case['name']; ?></a></div>
      <div class="description"><?php echo $case['description']; ?></div>

      <?php if ($case['rating']) { ?>
      <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $case['rating']; ?>.png" alt="<?php echo $case['reviews']; ?>" /></div>
      <?php } ?>
      <div class="cart">
        <a href="<?php echo $case['href']; ?>"><input type="button" value="<?php echo $button_cart; ?>" class="button" />
      </div>
	  <div class="compare">
	    <input type="button" onclick="addToCompare('<?php echo $case['case_id']; ?>');" value="<?php echo $button_compare; ?>" class="button" />
	  </div>    
	  <div class="compare"><a onclick="addToCompare('<?php echo $case['case_id']; ?>');"><?php echo $button_compare; ?></a></div>
	</div>
    <?php } ?>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#content input[name=\'search\']').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('select[name=\'category_id\']').bind('change', function() {
	if (this.value == '0') {
		$('input[name=\'sub_category\']').attr('disabled', 'disabled');
		$('input[name=\'sub_category\']').removeAttr('checked');
	} else {
		$('input[name=\'sub_category\']').removeAttr('disabled');
	}
});

$('select[name=\'category_id\']').trigger('change');

$('#button-search').bind('click', function() {
	url = 'index.php?route=case/search';
	
	var search = $('#content input[name=\'search\']').attr('value');
	
	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}
	var tag = $('#content input[name=\'tag\']').attr('value');
	if (tag) {
		url += '&tag=' + encodeURIComponent(tag);
	}
	var figure = $('#content input[name=\'figure\']').attr('value');
	if (figure) {
		url += '&figure=' + encodeURIComponent(figure);
	}
	var spread = $('#content input[name=\'spread\']').attr('value');
	if (spread) {
		url += '&spread=' + encodeURIComponent(spread);
	}
	var time = $('#content input[name=\'time\']').attr('value');
	if (time) {
		url += '&time=' + encodeURIComponent(time);
	}
	var tlocation = $('#content input[name=\'location\']').attr('value');
	if (tlocation) {
		url += '&location=' + encodeURIComponent(tlocation);
	}

	var category_id = $('#content select[name=\'category_id\']').attr('value');
	
	if (category_id > 0) {
		url += '&category_id=' + encodeURIComponent(category_id);
	}
	
	var sub_category = $('#content input[name=\'sub_category\']:checked').attr('value');
	
	if (sub_category) {
		url += '&sub_category=true';
	}
		
	var filter_description = $('#content input[name=\'description\']:checked').attr('value');
	
	if (filter_description) {
		url += '&description=true';
	}

	location = url;
});

function display(view) {
		$('.case-grid').attr('class', 'case-list');
		
		$('.case-list > div').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
			html += '</div>';			
			
			html += '<div class="left">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
						
			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';
						
			$(element).html(html);
		});		
		
		$('.display').html('<b><?php echo $text_display; ?></b> <?php echo $text_list; ?>');
		
		$.totalStorage('display', 'list'); 
}

view = $.totalStorage('display');

if (view) {
	display(view);
} else {
	display('list');
}
//--></script> 
<?php echo $footer; ?>