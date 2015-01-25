<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/case.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="left"><?php echo $column_tag; ?></td>
              <td class="left"><?php echo $column_figure; ?></td>
              <td class="left"><?php echo $column_spread; ?></td>
              <td class="left"><?php echo $column_time; ?></td>
              <td class="left"><?php echo $column_location; ?></td>
              <td class="center" width="50"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" size="10"/></td>
              <td><input type="text" name="filter_tag" value="<?php echo $filter_tag; ?>" size="15"/></td>
              <td><input type="text" name="filter_figure" value="<?php echo $filter_figure; ?>" size="15"/></td>
              <td><input type="text" name="filter_spread" value="<?php echo $filter_spread; ?>" size="15"/></td>
              <td><input type="text" name="filter_time" value="<?php echo $filter_time; ?>" size="15"/></td>
              <td><input type="text" name="filter_location" value="<?php echo $filter_location; ?>" size="15"/></td>
              <td align="center"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
            </tr>
            <?php if ($cases) { ?>
            <?php foreach ($cases as $case) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($case['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $case['case_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $case['case_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $case['name']; ?></td>
              <td class="left"><?php echo $case['model']; ?></td>   
              <td class="left"><?php echo $case['tag']; ?></td>      
              <td class="left"><?php echo $case['figure']; ?></td>    
              <td class="left"><?php echo $case['spread']; ?></td>    
              <td class="left"><?php echo $case['time']; ?></td>    
              <td class="left"><?php echo $case['location']; ?></td>       
              <td class="center"><?php foreach ($case['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/case&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	var filter_tag = $('input[name=\'filter_tag\']').attr('value');
	
	if (filter_tag) {
		url += '&filter_tag=' + encodeURIComponent(filter_tag);
	}
	var filter_figure = $('input[name=\'filter_figure\']').attr('value');
	
	if (filter_figure) {
		url += '&filter_figure=' + encodeURIComponent(filter_figure);
	}
	var filter_spread = $('input[name=\'filter_spread\']').attr('value');
	
	if (filter_spread) {
		url += '&filter_spread=' + encodeURIComponent(filter_spread);
	}
	var filter_time = $('input[name=\'filter_time\']').attr('value');
	
	if (filter_time) {
		url += '&filter_time=' + encodeURIComponent(filter_time);
	}
	var filter_location = $('input[name=\'filter_location\']').attr('value');
	
	if (filter_location) {
		url += '&filter_location=' + encodeURIComponent(filter_location);
	}
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	var filter_price = $('input[name=\'filter_price\']').attr('value');
	
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
	
	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
	
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/case/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.case_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/case/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.case_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script> 
<?php echo $footer; ?>