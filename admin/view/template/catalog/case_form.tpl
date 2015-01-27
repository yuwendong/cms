<?php echo $header; ?>
<script type="text/javascript" src="view/javascript/jquery/ajaxupload.js"></script> 
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
      <div id="tabs" class="htabs"><a href="#tab-data"><?php echo $tab_data; ?></a><a href="#tab-links"><?php echo $tab_links; ?></a><a href="#tab-excerpt"><?php echo $tab_excerpt; ?></a><a href="#tab-download"><?php echo $tab_download; ?></a></div>
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
              <td><?php echo $entry_abstract; ?></td>
              <td><input type="text" name="abstract" value="<?php echo $abstract; ?>" size="100px"/></td>
            </tr>
            <tr>
              <td><?php echo $entry_keywordlist; ?></td>
              <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" size="100px"/></td>
            </tr>
            <tr>
              <td><?php echo $entry_pie; ?></td>
              <td><?php echo $entry_news; ?>
              <input type="text" name="news_percent" value="<?php echo sprintf("%s",$news_percent*1); ?>" size="5px"/>
              <?php echo $entry_happy; ?>
              <input type="text" name="happy_percent" value="<?php echo sprintf("%s",$happy_percent*1); ?>" size="5px"/>
              <?php echo $entry_angry; ?>
              <input type="text" name="angry_percent" value="<?php echo sprintf("%s",$angry_percent*1); ?>" size="5px"/>
              <?php echo $entry_sad; ?>
              <input type="text" name="sad_percent" value="<?php echo sprintf("%s",$sad_percent*1); ?>" size="5px"/></td>
            </tr>
            <tr>
              <td><?php echo $entry_pointlist; ?></td>
              <td><?php echo $entry_point1; ?>&nbsp;&nbsp;
              <input type="text" name="point1" value="<?php echo sprintf("%s",$point1*1); ?>" size="5px"/>
              <?php echo $entry_point2; ?>&nbsp;&nbsp;
              <input type="text" name="point2" value="<?php echo sprintf("%s",$point2*1); ?>" size="5px"/>
              <?php echo $entry_point3; ?>&nbsp;&nbsp;
              <input type="text" name="point3" value="<?php echo sprintf("%s",$point3*1); ?>" size="5px"/>
              <?php echo $entry_point4; ?>&nbsp;&nbsp;
              <input type="text" name="point4" value="<?php echo sprintf("%s",$point4*1); ?>" size="5px"/>
              <?php echo $entry_point5; ?>&nbsp;&nbsp;
              <input type="text" name="point5" value="<?php echo sprintf("%s",$point5*1); ?>" size="5px"/><br />
              <?php echo $entry_point6; ?>&nbsp;&nbsp;
              <input type="text" name="point6" value="<?php echo sprintf("%s",$point6*1); ?>" size="5px"/>
              <?php echo $entry_point7; ?>&nbsp;&nbsp;
              <input type="text" name="point7" value="<?php echo sprintf("%s",$point7*1); ?>" size="5px"/>
              <?php echo $entry_point8; ?>&nbsp;&nbsp;
              <input type="text" name="point8" value="<?php echo sprintf("%s",$point8*1); ?>" size="5px"/>
              <?php echo $entry_point9; ?>&nbsp;&nbsp;
              <input type="text" name="point9" value="<?php echo sprintf("%s",$point9*1); ?>" size="5px"/>
              <?php echo $entry_point10; ?>
              <input type="text" name="point10" value="<?php echo sprintf("%s",$point10*1); ?>" size="5px"/></td>
            </tr>
            <tr>
              <td><?php echo $entry_citylist; ?></td>
              <td><?php echo $entry_city1; ?>&nbsp;&nbsp;
              <input type="text" name="city1" value="<?php echo $city1; ?>" size="5px"/>
              <?php echo $entry_city2; ?>&nbsp;&nbsp;
              <input type="text" name="city2" value="<?php echo $city2; ?>" size="5px"/>
              <?php echo $entry_city3; ?>&nbsp;&nbsp;&nbsp;
              <input type="text" name="city3" value="<?php echo $city3; ?>" size="5px"/>
              <?php echo $entry_city4; ?>&nbsp;&nbsp;
              <input type="text" name="city4" value="<?php echo $city4; ?>" size="5px"/>
              <?php echo $entry_city5; ?>&nbsp;&nbsp;
              <input type="text" name="city5" value="<?php echo $city5; ?>" size="5px"/><br />
              <?php echo $entry_city6; ?>&nbsp;&nbsp;
              <input type="text" name="city6" value="<?php echo $city6; ?>" size="5px"/>
              <?php echo $entry_city7; ?>&nbsp;&nbsp;
              <input type="text" name="city7" value="<?php echo $city7; ?>" size="5px"/>
              <?php echo $entry_city8; ?>&nbsp;&nbsp;&nbsp;
              <input type="text" name="city8" value="<?php echo $city8; ?>" size="5px"/>
              <?php echo $entry_city9; ?>&nbsp;&nbsp;
              <input type="text" name="city9" value="<?php echo $city9; ?>" size="5px"/>
              <?php echo $entry_city10; ?>
              <input type="text" name="city10" value="<?php echo $city10; ?>" size="5px"/><br />
              <?php echo $entry_city11; ?>
              <input type="text" name="city11" value="<?php echo $city11; ?>" size="5px"/>
              <?php echo $entry_city12; ?>
              <input type="text" name="city12" value="<?php echo $city12; ?>" size="5px"/>
              <?php echo $entry_city13; ?>
              <input type="text" name="city13" value="<?php echo $city13; ?>" size="5px"/>
              <?php echo $entry_city14; ?>
              <input type="text" name="city14" value="<?php echo $city14; ?>" size="5px"/>
              <?php echo $entry_city15; ?>
              <input type="text" name="city15" value="<?php echo $city15; ?>" size="5px"/><br />
              <?php echo $entry_city16; ?>
              <input type="text" name="city16" value="<?php echo $city16; ?>" size="5px"/>
              <?php echo $entry_city17; ?>
              <input type="text" name="city17" value="<?php echo $city17; ?>" size="5px"/>
              <?php echo $entry_city18; ?>
              <input type="text" name="city18" value="<?php echo $city18; ?>" size="5px"/>
              <?php echo $entry_city19; ?>
              <input type="text" name="city19" value="<?php echo $city19; ?>" size="5px"/>
              <?php echo $entry_city20; ?>
              <input type="text" name="city20" value="<?php echo $city20; ?>" size="5px"/></td>
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
                <td><input type="text" name="case_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['tag'] : ''; ?>" size="160" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_figure; ?></td>
                <td><input type="text" name="case_description[<?php echo $language['language_id']; ?>][figure]" value="<?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['figure'] : ''; ?>" size="160" /></td>
              </tr> 
              <tr>
                <td><?php echo $entry_spread; ?></td>
                <td><input type="text" name="case_description[<?php echo $language['language_id']; ?>][spread]" value="<?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['spread'] : ''; ?>" size="160" /></td>
              </tr> 
              <tr>
                <td><?php echo $entry_time; ?></td>
                <td><input type="text" name="case_description[<?php echo $language['language_id']; ?>][time]" value="<?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['time'] : ''; ?>" size="160" /></td>
              </tr> 
              <tr>
                <td><?php echo $entry_location; ?></td>
                <td><input type="text" name="case_description[<?php echo $language['language_id']; ?>][location]" value="<?php echo isset($case_description[$language['language_id']]) ? $case_description[$language['language_id']]['location'] : ''; ?>" size="160" /></td>
              </tr> 
            </table>
          </div>
          <?php } ?>
        </div>
        <div id="tab-links">
          <table class="form">
            <tr>
              <td><?php echo $entry_totalrelated; ?></td>
              <td><div id="case-totalrelated" class="totalscrollbox">
                  <?php foreach ($case_totalrelated as $case_totalrelated) { ?>
                  <div id="case-totalrelated<?php echo $case_totalrelated['related_id']; ?>"  style="background-color:yellow;"> <?php echo $case_totalrelated['name']; ?> <span>( <?php echo number_format($case_totalrelated['weight'], 2, '.', ''); ?> )&nbsp;&nbsp;</span>
				  </div>				  
                  <?php } ?>
                </div></td>
            </tr>
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
            <tr>
              <td><?php echo $entry_figurerelated; ?></td>
              <td><div id="case-figurerelated" class="scrollbox">
                  <?php $class = 'odd'; ?>
				  <?php $figurerelated_row = 0; ?>
                  <?php foreach ($case_figurerelated as $case_figurerelated) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="case-figurerelated<?php echo $case_figurerelated['related_id']; ?>" class="<?php echo $class; ?>"> <?php echo $case_figurerelated['name']; ?> (<?php echo $case_figurerelated['weight']; ?>) - <?php echo $case_figurerelated['value']; ?> 
                    <?php if($case_figurerelated['status']=='1') { ?>
					<img src="view/image/remove.png" alt="" />
					<?php }elseif($case_figurerelated['status']=='0'){ ?>
					<img src="view/image/success.png" alt="" />
					<?php } ?>
					<input type="hidden" name="case_figurerelated[<?php echo $figurerelated_row; ?>][status]" value="<?php echo $case_figurerelated['status']; ?>" />
					<input type="hidden" name="case_figurerelated[<?php echo $figurerelated_row; ?>][related_id]" value="<?php echo $case_figurerelated['related_id']; ?>" />
					<input type="hidden" name="case_figurerelated[<?php echo $figurerelated_row; ?>][value]" value="<?php echo $case_figurerelated['value']; ?>" />
					<input type="hidden" name="case_figurerelated[<?php echo $figurerelated_row; ?>][weight]" value="<?php echo $case_figurerelated['weight']; ?>" />
				  </div>				  
				  <?php $figurerelated_row++; ?>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_spreadrelated; ?></td>
              <td><div id="case-spreadrelated" class="scrollbox">
                  <?php $class = 'odd'; ?>
				  <?php $spreadrelated_row = 0; ?>
                  <?php foreach ($case_spreadrelated as $case_spreadrelated) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="case-spreadrelated<?php echo $case_spreadrelated['related_id']; ?>" class="<?php echo $class; ?>"> <?php echo $case_spreadrelated['name']; ?> (<?php echo $case_spreadrelated['weight']; ?>) - <?php echo $case_spreadrelated['value']; ?> 
                    <?php if($case_spreadrelated['status']=='1') { ?>
					<img src="view/image/remove.png" alt="" />
					<?php }elseif($case_spreadrelated['status']=='0'){ ?>
					<img src="view/image/success.png" alt="" />
					<?php } ?>
					<input type="hidden" name="case_spreadrelated[<?php echo $spreadrelated_row; ?>][status]" value="<?php echo $case_spreadrelated['status']; ?>" />
					<input type="hidden" name="case_spreadrelated[<?php echo $spreadrelated_row; ?>][related_id]" value="<?php echo $case_spreadrelated['related_id']; ?>" />
					<input type="hidden" name="case_spreadrelated[<?php echo $spreadrelated_row; ?>][value]" value="<?php echo $case_spreadrelated['value']; ?>" />
					<input type="hidden" name="case_spreadrelated[<?php echo $spreadrelated_row; ?>][weight]" value="<?php echo $case_spreadrelated['weight']; ?>" />
				  </div>				  
				  <?php $spreadrelated_row++; ?>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_locationrelated; ?></td>
              <td><div id="case-locationrelated" class="scrollbox">
                  <?php $class = 'odd'; ?>
				  <?php $locationrelated_row = 0; ?>
                  <?php foreach ($case_locationrelated as $case_locationrelated) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="case-locationrelated<?php echo $case_locationrelated['related_id']; ?>" class="<?php echo $class; ?>"> <?php echo $case_locationrelated['name']; ?> (<?php echo $case_locationrelated['weight']; ?>) - <?php echo $case_locationrelated['value']; ?> 
                    <?php if($case_locationrelated['status']=='1') { ?>
					<img src="view/image/remove.png" alt="" />
					<?php }elseif($case_locationrelated['status']=='0'){ ?>
					<img src="view/image/success.png" alt="" />
					<?php } ?>
					<input type="hidden" name="case_locationrelated[<?php echo $locationrelated_row; ?>][status]" value="<?php echo $case_locationrelated['status']; ?>" />
					<input type="hidden" name="case_locationrelated[<?php echo $locationrelated_row; ?>][related_id]" value="<?php echo $case_locationrelated['related_id']; ?>" />
					<input type="hidden" name="case_locationrelated[<?php echo $locationrelated_row; ?>][value]" value="<?php echo $case_locationrelated['value']; ?>" />
					<input type="hidden" name="case_locationrelated[<?php echo $locationrelated_row; ?>][weight]" value="<?php echo $case_locationrelated['weight']; ?>" />
				  </div>				  
				  <?php $locationrelated_row++; ?>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_timerelated; ?></td>
              <td><div id="case-timerelated" class="scrollbox">
                  <?php $class = 'odd'; ?>
				  <?php $timerelated_row = 0; ?>
                  <?php foreach ($case_timerelated as $case_timerelated) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="case-timerelated<?php echo $case_timerelated['related_id']; ?>" class="<?php echo $class; ?>"> <?php echo $case_timerelated['name']; ?> (<?php echo $case_timerelated['weight']; ?>) - <?php echo $case_timerelated['value']; ?> 
                    <?php if($case_timerelated['status']=='1') { ?>
					<img src="view/image/remove.png" alt="" />
					<?php }elseif($case_timerelated['status']=='0'){ ?>
					<img src="view/image/success.png" alt="" />
					<?php } ?>
					<input type="hidden" name="case_timerelated[<?php echo $timerelated_row; ?>][status]" value="<?php echo $case_timerelated['status']; ?>" />
					<input type="hidden" name="case_timerelated[<?php echo $timerelated_row; ?>][related_id]" value="<?php echo $case_timerelated['related_id']; ?>" />
					<input type="hidden" name="case_timerelated[<?php echo $timerelated_row; ?>][value]" value="<?php echo $case_timerelated['value']; ?>" />
					<input type="hidden" name="case_timerelated[<?php echo $timerelated_row; ?>][weight]" value="<?php echo $case_timerelated['weight']; ?>" />
				  </div>				  
				  <?php $timerelated_row++; ?>
                  <?php } ?>
                </div></td>
            </tr>
          </table>
        </div>
        <div id="tab-download">
          <table id="download" class="list">
            <thead>
              <tr>
                <td class="right"><?php echo $entry_download_name; ?></td>
                <td class="right"><?php echo $entry_download_filename; ?></td>
                <td ></td>
              </tr>
            </thead>
            <?php $download_row = 0; ?>
            <?php foreach ($case_downloads as $case_download) { ?>
            <tbody id="download-row<?php echo $download_row; ?>">
              <tr>
                <td class="right"><input type="hidden" name="case_download[<?php echo $download_row; ?>][case_download_id]" value="<?php echo $case_download['case_download_id']; ?>"/><input type="text" name="case_download[<?php echo $download_row; ?>][name]" value="<?php echo $case_download['name']; ?>"/></td>
                <td class="right"><input type="hidden" name="case_download[<?php echo $download_row; ?>][filename]" value="<?php echo $case_download['filename']; ?>"/><input type="text" name="case_download[<?php echo $download_row; ?>][mask]" value="<?php echo $case_download['mask']; ?>" size="100" readonly="true"/>&nbsp;&nbsp;&nbsp;&nbsp;<a id="button-upload<?php echo $download_row; ?>" class="button" onclick="uploadFile(<?php echo $download_row; ?>);"><?php echo $button_upload; ?></a> <a id="button-view<?php echo $download_row; ?>" class="button" onclick="window.open('<?php echo $case_download['href']; ?>');"><?php echo $button_view; ?></a></td>
                <td class="left"><a onclick="$('#download-row<?php echo $download_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
				<script type="text/javascript"><!--
				new AjaxUpload('#button-upload'+<?php echo $download_row; ?>, {
					action: 'index.php?route=catalog/download/upload&token=<?php echo $token; ?>',
					name: 'file',
					autoSubmit: true,
					responseType: 'json',
					onSubmit: function(file, extension) {
						$('#button-upload').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
						$('#button-upload').attr('disabled', true);
					},
					onComplete: function(file, json) {
						$('#button-upload').attr('disabled', false);						
						if (json['success']) {
							alert(json['success']);
							$('input[name=\'case_download[<?php echo $download_row; ?>][mask]\']').attr('value', json['mask']);
							$('input[name=\'case_download[<?php echo $download_row; ?>][filename]\']').attr('value', json['filename']);
							var name = json['mask'];
							name = name.substring(0,name.lastIndexOf('.'));
							if($('input[name=\'case_download[<?php echo $download_row; ?>][name]\']').attr('value')==''){
								$('input[name=\'case_download[<?php echo $download_row; ?>][name]\']').attr('value', name);
							}
						}						
						if (json['error']) {
							alert(json['error']);
						}						
						$('.loading').remove();	
					}
				});
				//--></script> 
			  </tr>
            </tbody>
            <?php $download_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addDownload();" class="button"><?php echo $button_add_download; ?></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
		<div id="tab-excerpt">
          <table id="excerpt" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_excerpt_type; ?></td>
                <td width=600 class="left"><?php echo $entry_excerpt_body; ?></td>
                <td ></td>
              </tr>
            </thead>
            <?php $excerpt_row = 0; ?>
            <?php foreach ($case_excerpts as $case_excerpt) { ?>
            <tbody id="excerpt-row<?php echo $excerpt_row; ?>">
              <tr>
			  	<td class="left"><select name="case_excerpt[<?php echo $excerpt_row; ?>][contenttype_id]" style="width:100px;">
                    <option value="0"><?php echo $text_select; ?></option>
                    <?php foreach ($content_types as $contenttype_id) { ?>
                    <?php if ($contenttype_id['contenttype_id'] == $case_excerpt['contenttype_id']) { ?>
                    <option value="<?php echo $contenttype_id['contenttype_id']; ?>" selected="selected"><?php echo $contenttype_id['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $contenttype_id['contenttype_id']; ?>"><?php echo $contenttype_id['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="left"><textarea name="case_excerpt[<?php echo $excerpt_row; ?>][content]" cols="140" rows="10"><?php echo $case_excerpt['content']; ?></textarea></td>
                <td class="left"><a onclick="$('#excerpt-row<?php echo $excerpt_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $excerpt_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addExcerpt();" class="button"><?php echo $button_add_excerpt; ?></a></td>
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
$('#case-figurerelated div img').live('click', function() {
	if($(this).attr("src")=="view/image/success.png"){
		$(this).parent().find("input[type=hidden]:first").attr("value","1");
		$(this).attr("src","view/image/remove.png");
	}else{
		$(this).parent().find("input[type=hidden]:first").attr("value","0");
		$(this).attr("src","view/image/success.png");
	}
});
$('#case-spreadrelated div img').live('click', function() {
	if($(this).attr("src")=="view/image/success.png"){
		$(this).parent().find("input[type=hidden]:first").attr("value","1");
		$(this).attr("src","view/image/remove.png");
	}else{
		$(this).parent().find("input[type=hidden]:first").attr("value","0");
		$(this).attr("src","view/image/success.png");
	}
});
$('#case-locationrelated div img').live('click', function() {
	if($(this).attr("src")=="view/image/success.png"){
		$(this).parent().find("input[type=hidden]:first").attr("value","1");
		$(this).attr("src","view/image/remove.png");
	}else{
		$(this).parent().find("input[type=hidden]:first").attr("value","0");
		$(this).attr("src","view/image/success.png");
	}
});
$('#case-timerelated div img').live('click', function() {
	if($(this).attr("src")=="view/image/success.png"){
		$(this).parent().find("input[type=hidden]:first").attr("value","1");
		$(this).attr("src","view/image/remove.png");
	}else{
		$(this).parent().find("input[type=hidden]:first").attr("value","0");
		$(this).attr("src","view/image/success.png");
	}
});
//--></script> 
<script type="text/javascript"><!--
var excerpt_row = <?php echo $excerpt_row; ?>;

function addExcerpt() {
	html  = '<tbody id="excerpt-row' + excerpt_row + '">';
	html += '  <tr>'; 
	html += '    <td class="left"><select style="width:100px;" name="case_excerpt[' + excerpt_row + '][contenttype_id]"><option value="0"><?php echo $text_select; ?></option>';
    <?php foreach ($content_types as $content_type) { ?>
    html += '      <option value="<?php echo $content_type['contenttype_id']; ?>"><?php echo ($content_type['name']); ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="left"><textarea name="case_excerpt[' + excerpt_row + '][content]" cols="140" rows="10"></textarea></td>';
	html += '    <td class="left"><a onclick="$(\'#excerpt-row' + excerpt_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
    html += '</tbody>';
	
	$('#excerpt tfoot').before(html);
 
	$('#excerpt-row' + excerpt_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	excerpt++;
}
//--></script> 
<script type="text/javascript"><!--
var download_row = <?php echo $download_row; ?>;

function addDownload() {
	html  = '<tbody id="download-row' + download_row + '">';
	html += '  <tr>'; 		
    html += '    <td class="right"><input type="text" name="case_download[' + download_row + '][name]" value=""/></td>';
    html += '    <td class="right"><input type="hidden" name="case_download[' + download_row + '][filename]" value=""/><input type="text" name="case_download[' + download_row + '][mask]" value="" size="100" readonly="true"/>&nbsp;&nbsp;&nbsp;&nbsp;<a id="button-upload' + download_row + '" class="button" onclick="uploadFile(' + download_row + ');"><?php echo $button_upload; ?></a></td>/></td>';
	html += '    <td class="left"><a onclick="$(\'#download-row' + download_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '				<script type="text/javascript">';
	html += '				new AjaxUpload(\'#button-upload' + download_row + '\', {';
	html += '					action: \'index.php?route=catalog/download/upload&token=<?php echo $token; ?>\',';
	html += '					name: \'file\',';
	html += '					autoSubmit: true,';
	html += '					responseType:\'json\',';
	html += '					onSubmit: function(file, extension) {';
	html += '						$(\'#button-upload\').after(\'<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />\');';
	html += '						$(\'#button-upload\').attr(\'disabled\', true);';
	html += '					},';
	html += '					onComplete: function(file, json) {';
	html += '						$(\'#button-upload\').attr(\'disabled\', false);	';					
	html += '						if (json[\'success\']) {';
	html += '							alert(json[\'success\']);';
	html += '							$(\'input[name=\\\'case_download['+download_row+'][mask]\\\']\').attr(\'value\', json[\'mask\']);';
	html += '							$(\'input[name=\\\'case_download['+download_row+'][filename]\\\']\').attr(\'value\', json[\'filename\']);';
	html += '							var name = json[\'mask\'];';
	html += '							name = name.substring(0,name.lastIndexOf(\'.\'));';
	html += '							if($(\'input[name=\\\'case_download['+download_row+'][name]\\\']\').attr(\'value\')==\'\'){';
	html += '								$(\'input[name=\\\'case_download['+download_row+'][name]\\\']\').attr(\'value\', name);';
	html += '							}';
	html += '						}						';
	html += '						if (json[\'error\']) {';
	html += '							alert(json[\'error\']);';
	html += '						}						';
	html += '						$(\'.loading\').remove();	';
	html += '					}';
	html += '				});';
	html += '				</script> ';
	html += '  </tr>';
    html += '</tbody>';
	
	$('#download tfoot').before(html);
 
	$('#download-row' + download_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	download++;
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