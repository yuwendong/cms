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
  <?php if (!empty($_SESSION['success'])) { ?>
  <div class="success"><?php echo $_SESSION['success']; ?></div>
  <?php unset($_SESSION['success']); ?>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/case.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-data"><?php echo $tab_data; ?></a><a href="#tab-links"><?php echo $tab_links; ?></a><a href="#tab-excerpt"><?php echo $tab_excerpt; ?></a><a href="#tab-image"><?php echo $tab_image; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-data">
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
				<tr>
				  <td><span class="required">*</span> <?php echo $entry_model; ?></td>
				  <td><input type="text" name="model" value="<?php echo $model; ?>" />
					<?php if ($error_model) { ?>
					<span class="error"><?php echo $error_model; ?></span>
					<?php } ?></td>				
					  <input type="hidden" name="case_store[]" value='0'/>
					  <input type="hidden" name="stock_status_id" value='1'/>
					  <input type="hidden" name="quantity" value='100'/>
				</tr>     
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td><input type="text" name="case_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['name'] : ''; ?>" />
                  <?php if (isset($error_name[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                  <?php } ?></td>
              </tr> 

            <tr>
              <td><?php echo $entry_category; ?></td>
              <td><input type="text" name="category" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="case-category" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($case_categories as $case_category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="case-category<?php echo $case_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $case_category['name']; ?><img src="view/image/delete.png" alt="" />
                    <input type="hidden" name="case_category[]" value="<?php echo $case_category['category_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr> 			  
              <tr>
                <td><?php echo $entry_description; ?></td>
                <td><textarea name="case_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['description'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_tag; ?></td>
                <td><input type="text" name="case_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['tag'] : ''; ?>" size="80" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_figure; ?></td>
                <td><input type="text" name="case_description[<?php echo $language['language_id']; ?>][figure]" value="<?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['figure'] : ''; ?>" size="80" /></td>
              </tr> 
            </table>
          </div>
          <?php } ?>
        </div>
        <div id="tab-links">
          <table class="form">
            <tr>
              <td><?php echo $entry_related; ?></td>
              <td><input type="text" name="related" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="case-related" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($case_related as $case_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="case-related<?php echo $case_related['case_id']; ?>" class="<?php echo $class; ?>"> <?php echo $case_related['name']; ?><img src="view/image/delete.png" alt="" />
                    <input type="hidden" name="case_related[]" value="<?php echo $case_related['case_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_tagrelated; ?></td>
              <td><div id="case-tagrelated" class="scrollbox">
                  <?php $class = 'odd'; ?>
				  <?php $tagrelated_row = 0; ?>
                  <?php foreach ($case_tagrelated as $case_tagrelated) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="case-tagrelated<?php echo $case_tagrelated['related_id']; ?>" class="<?php echo $class; ?>"> <?php echo $case_tagrelated['name']; ?> (<?php echo $case_tagrelated['weight']; ?>) - <?php echo $case_tagrelated['value']; ?> 
                    <?php if($case_tagrelated['status']=='1') { ?>
					<img src="view/image/remove.png" alt="" />
					<?php }elseif($case_tagrelated['status']=='0'){ ?>
					<img src="view/image/success.png" alt="" />
					<?php } ?>
					<input type="hidden" name="case_tagrelated[<?php echo $tagrelated_row; ?>][status]" value="<?php echo $case_tagrelated['status']; ?>" />
					<input type="hidden" name="case_tagrelated[<?php echo $tagrelated_row; ?>][related_id]" value="<?php echo $case_tagrelated['related_id']; ?>" />
					<input type="hidden" name="case_tagrelated[<?php echo $tagrelated_row; ?>][value]" value="<?php echo $case_tagrelated['value']; ?>" />
					<input type="hidden" name="case_tagrelated[<?php echo $tagrelated_row; ?>][weight]" value="<?php echo $case_tagrelated['weight']; ?>" />
				  </div>				  
				  <?php $tagrelated_row++; ?>
                  <?php } ?>
                </div></td>
            </tr>
          </table>
        </div>
        <div id="tab-excerpt">
          <table id="excerpt" class="list">
            <thead>
              <tr>
                <td width=950 class="right"><?php echo $entry_excerpt_body; ?></td>
                <td ></td>
              </tr>
            </thead>
            <?php $excerpt_row = 0; ?>
            <?php foreach ($case_excerpts as $case_excerpt) { ?>
            <tbody id="excerpt-row<?php echo $excerpt_row; ?>">
              <tr>
                <td class="right"><textarea name="case_excerpt[<?php echo $excerpt_row; ?>][content]" cols="140" rows="10"><?php echo $case_excerpt['content']; ?></textarea></td>
                <td class="left"><a onclick="$('#excerpt-row<?php echo $excerpt_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $excerpt_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="1"></td>
                <td class="left"><a onclick="addExcerpt();" class="button"><?php echo $button_add_excerpt; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
		<div id="tab-image">
          <table id="images" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_image; ?></td>
                <td class="right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $image_row = 0; ?>
            <?php foreach ($case_images as $case_image) { ?>
            <tbody id="image-row<?php echo $image_row; ?>">
              <tr>
                <td class="left"><div class="image"><img src="<?php echo $case_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                    <input type="hidden" name="case_image[<?php echo $image_row; ?>][image]" value="<?php echo $case_image['image']; ?>" id="image<?php echo $image_row; ?>" />
                    <br />
                    <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
                <td class="right"><input type="text" name="case_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $case_image['sort_order']; ?>" size="2" /></td>
                <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $image_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addImage();" class="button"><?php echo $button_add_image; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.manufacturer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'manufacturer\']').attr('value', ui.item.label);
		$('input[name=\'manufacturer_id\']').attr('value', ui.item.value);
	
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

// KeyFigures
$('input[name=\'keyfigures\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/keyfigures/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.keyfigures_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#case-keyfigures' + ui.item.value).remove();
		
		$('#case-keyfigures').append('<div id="case-keyfigures' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="case_keyfigures[]" value="' + ui.item.value + '" /></div>');

		$('#case-keyfigures div:odd').attr('class', 'odd');
		$('#case-keyfigures div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#case-keyfigures div img').live('click', function() {
	$(this).parent().remove();
	
	$('#case-keyfigures div:odd').attr('class', 'odd');
	$('#case-keyfigures div:even').attr('class', 'even');	
});


// Labels
$('input[name=\'label\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/label/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.label_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#case-label' + ui.item.value).remove();
		
		$('#case-label').append('<div id="case-label' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="case_label[]" value="' + ui.item.value + '" /></div>');

		$('#case-label div:odd').attr('class', 'odd');
		$('#case-label div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#case-label div img').live('click', function() {
	$(this).parent().remove();
	
	$('#case-label div:odd').attr('class', 'odd');
	$('#case-label div:even').attr('class', 'even');	
});


// Category
$('input[name=\'category\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.category_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#case-category' + ui.item.value).remove();
		
		$('#case-category').append('<div id="case-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="case_category[]" value="' + ui.item.value + '" /></div>');

		$('#case-category div:odd').attr('class', 'odd');
		$('#case-category div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#case-category div img').live('click', function() {
	$(this).parent().remove();
	
	$('#case-category div:odd').attr('class', 'odd');
	$('#case-category div:even').attr('class', 'even');	
});

// Filter
$('input[name=\'filter\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.filter_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#case-filter' + ui.item.value).remove();
		
		$('#case-filter').append('<div id="case-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="case_filter[]" value="' + ui.item.value + '" /></div>');

		$('#case-filter div:odd').attr('class', 'odd');
		$('#case-filter div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#case-filter div img').live('click', function() {
	$(this).parent().remove();
	
	$('#case-filter div:odd').attr('class', 'odd');
	$('#case-filter div:even').attr('class', 'even');	
});

// Downloads
$('input[name=\'download\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.download_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#case-download' + ui.item.value).remove();
		
		$('#case-download').append('<div id="case-download' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="case_download[]" value="' + ui.item.value + '" /></div>');

		$('#case-download div:odd').attr('class', 'odd');
		$('#case-download div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#case-download div img').live('click', function() {
	$(this).parent().remove();
	
	$('#case-download div:odd').attr('class', 'odd');
	$('#case-download div:even').attr('class', 'even');	
});

// Related
$('input[name=\'related\']').autocomplete({
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
		$('#case-related' + ui.item.value).remove();
		
		$('#case-related').append('<div id="case-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="case_related[]" value="' + ui.item.value + '" /></div>');

		$('#case-related div:odd').attr('class', 'odd');
		$('#case-related div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#case-related div img').live('click', function() {
	$(this).parent().remove();
	
	$('#case-related div:odd').attr('class', 'odd');
	$('#case-related div:even').attr('class', 'even');	
});

$('#case-tagrelated div img').live('click', function() {
	//$(this).parent().remove();
	if($(this).attr("src")=="view/image/success.png"){
		$(this).parent().find("input[type=hidden]:first").attr("value","1");
		$(this).attr("src","view/image/remove.png");
	}else{
		$(this).parent().find("input[type=hidden]:first").attr("value","0");
		$(this).attr("src","view/image/success.png");
	}
	//$('#case-tagrelated div:odd').attr('class', 'odd');
	//$('#case-tagrelated div:even').attr('class', 'even');	
});
//--></script> 
<script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
	html  = '<tbody id="attribute-row' + attribute_row + '">';
    html += '  <tr>';
	html += '    <td class="left"><input type="text" name="case_attribute[' + attribute_row + '][name]" value="" /><input type="hidden" name="case_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '    <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<textarea name="case_attribute[' + attribute_row + '][case_attribute_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"></textarea><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" /><br />';
    <?php } ?>
	html += '    </td>';
	html += '    <td class="left"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
    html += '  </tr>';	
    html += '</tbody>';
	
	$('#attribute tfoot').before(html);
	
	attributeautocomplete(attribute_row);
	
	attribute_row++;
}

function attributeautocomplete(attribute_row) {
	$('input[name=\'case_attribute[' + attribute_row + '][name]\']').catcomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'case_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'case_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
      		return false;
   		}
	});
}

$('#attribute tbody').each(function(index, element) {
	attributeautocomplete(index);
});
//--></script> 
<script type="text/javascript"><!--	
var option_row = <?php echo $option_row; ?>;

$('input[name=\'option\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item.category,
						label: item.name,
						value: item.option_id,
						type: item.type,
						option_value: item.option_value
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		html  = '<div id="tab-option-' + option_row + '" class="vtabs-content">';
		html += '	<input type="hidden" name="case_option[' + option_row + '][case_option_id]" value="" />';
		html += '	<input type="hidden" name="case_option[' + option_row + '][name]" value="' + ui.item.label + '" />';
		html += '	<input type="hidden" name="case_option[' + option_row + '][option_id]" value="' + ui.item.value + '" />';
		html += '	<input type="hidden" name="case_option[' + option_row + '][type]" value="' + ui.item.type + '" />';
		html += '	<table class="form">';
		html += '	  <tr>';
		html += '		<td><?php echo $entry_required; ?></td>';
		html += '       <td><select name="case_option[' + option_row + '][required]">';
		html += '	      <option value="1"><?php echo $text_yes; ?></option>';
		html += '	      <option value="0"><?php echo $text_no; ?></option>';
		html += '	    </select></td>';
		html += '     </tr>';
		
		if (ui.item.type == 'text') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="case_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}
		
		if (ui.item.type == 'textarea') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><textarea name="case_option[' + option_row + '][option_value]" cols="40" rows="5"></textarea></td>';
			html += '     </tr>';						
		}
		 
		if (ui.item.type == 'file') {
			html += '     <tr style="display: none;">';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="case_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';			
		}
						
		if (ui.item.type == 'date') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="case_option[' + option_row + '][option_value]" value="" class="date" /></td>';
			html += '     </tr>';			
		}
		
		if (ui.item.type == 'datetime') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="case_option[' + option_row + '][option_value]" value="" class="datetime" /></td>';
			html += '     </tr>';			
		}
		
		if (ui.item.type == 'time') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="case_option[' + option_row + '][option_value]" value="" class="time" /></td>';
			html += '     </tr>';			
		}
		
		html += '  </table>';
			
		if (ui.item.type == 'select' || ui.item.type == 'radio' || ui.item.type == 'checkbox' || ui.item.type == 'image') {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>'; 
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="right"><?php echo $entry_price; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
			html += '        <td class="right"><?php echo $entry_weight; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addOptionValue(' + option_row + ');" class="button"><?php echo $button_add_option_value; ?></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
            html += '  <select id="option-values' + option_row + '" style="display: none;">';
			
            for (i = 0; i < ui.item.option_value.length; i++) {
				html += '  <option value="' + ui.item.option_value[i]['option_value_id'] + '">' + ui.item.option_value[i]['name'] + '</option>';
            }

            html += '  </select>';			
			html += '</div>';	
		}
		
		$('#tab-option').append(html);
		
		$('#option-add').before('<a href="#tab-option-' + option_row + '" id="option-' + option_row + '">' + ui.item.label + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#option-' + option_row + '\').remove(); $(\'#tab-option-' + option_row + '\').remove(); $(\'#vtab-option a:first\').trigger(\'click\'); return false;" /></a>');
		
		$('#vtab-option a').tabs();
		
		$('#option-' + option_row).trigger('click');		
		
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});	
			
		$('.time').timepicker({timeFormat: 'h:m'});	
				
		option_row++;
		
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});
//--></script> 
<script type="text/javascript"><!--		
var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_row) {	
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][option_value_id]">';
	html += $('#option-values' + option_row).html();
	html += '    </select><input type="hidden" name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][case_option_value_id]" value="" /></td>';
	html += '    <td class="right"><input type="text" name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][quantity]" value="" size="3" /></td>'; 
	html += '    <td class="left"><select name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][subtract]">';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="0"><?php echo $text_no; ?></option>';
	html += '    </select></td>';
	html += '    <td class="right"><select name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][price_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][price]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][points_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][points]" value="" size="5" /></td>';	
	html += '    <td class="right"><select name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][weight_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" name="case_option[' + option_row + '][case_option_value][' + option_value_row + '][weight]" value="" size="5" /></td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#option-value' + option_row + ' tfoot').before(html);

	option_value_row++;
}
//--></script> 
<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tbody id="discount-row' + discount_row + '">';
	html += '  <tr>'; 
    html += '    <td class="left"><select name="case_discount[' + discount_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '    </select></td>';		
    html += '    <td class="right"><input type="text" name="case_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
    html += '    <td class="right"><input type="text" name="case_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text" name="case_discount[' + discount_row + '][price]" value="" /></td>';
    html += '    <td class="left"><input type="text" name="case_discount[' + discount_row + '][date_start]" value="" class="date" /></td>';
	html += '    <td class="left"><input type="text" name="case_discount[' + discount_row + '][date_end]" value="" class="date" /></td>';
	html += '    <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';	
    html += '</tbody>';
	
	$('#discount tfoot').before(html);
		
	$('#discount-row' + discount_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	discount_row++;
}
//--></script> 
<script type="text/javascript"><!--
var content_row = <?php echo $content_row; ?>;

function addContent() {
	html  = '<tbody id="content-row' + content_row + '">';
	html += '  <tr>'; 		
    html += '    <td class="left"><input type="text" name="case_content[' + content_row + '][title]" value=""/></td>';
	html += '    <td class="left"><input type="text" name="case_content[' + content_row + '][url]" value=""  size="2" /></td>';
    html += '    <td class="left"><textarea name="case_content[' + content_row + '][content]" cols="40" rows="5"></textarea></td>';
	html += '    <td class="left"><input type="text" name="case_content[' + content_row + '][keyword]" value=""/></td>';
	html += '    <td class="left"><input type="text" name="case_content[' + content_row + '][date_file]" size="15" value=""/></td>';
	html += '    <td class="left"><select style="width:80px;" name="case_content[' + content_row + '][contenttype_id]"><option value="0"><?php echo $text_select; ?></option>';
    <?php foreach ($content_types as $content_type) { ?>
    html += '      <option value="<?php echo $content_type['contenttype_id']; ?>"><?php echo ($content_type['name']); ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="left"><select style="width:80px;" name="case_content[' + content_row + '][contentcolumn_id]"><option value="0"><?php echo $text_select; ?></option>';
    <?php foreach ($content_columns as $content_column) { ?>
    html += '      <option value="<?php echo $content_column['contentcolumn_id']; ?>"><?php echo ($content_column['name']); ?></option>';
    <?php } ?>
    html += '    </select></td>';
	html += '    <td class="left"><a onclick="$(\'#content-row' + content_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
    html += '</tbody>';
	
	$('#ccontent tfoot').before(html);
 
	$('#content-row' + content_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	content_row++;
}
//--></script> 
<script type="text/javascript"><!--
var excerpt_row = <?php echo $excerpt_row; ?>;

function addExcerpt() {
	html  = '<tbody id="excerpt-row' + excerpt_row + '">';
	html += '  <tr>'; 		
    html += '    <td class="right"><textarea name="case_excerpt[' + excerpt_row + '][content]" cols="140" rows="10"></textarea></td>';
	html += '    <td class="left"><a onclick="$(\'#excerpt-row' + excerpt_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
    html += '</tbody>';
	
	$('#excerpt tfoot').before(html);
 
	$('#excerpt-row' + excerpt_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	excerpt++;
}
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="case_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
	html += '    <td class="right"><input type="text" name="case_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#images tfoot').before(html);
	
	image_row++;
}
//--></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
$('#vtab-option a').tabs();





//--></script>

<?php echo $footer; ?>