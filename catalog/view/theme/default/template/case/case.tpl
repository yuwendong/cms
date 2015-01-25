<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="case-info">
    <div class="right">
      <div class="description">
        <div style="float:left;width:200px;"><b><?php echo $text_model; ?></b><?php echo $model; ?></div>
		<div style="float:right;text-align:right;width:200px;"><input type="button" value="<?php echo $button_compare; ?>" onclick="addToCompare('<?php echo $case_id; ?>');" class="button" /></div><br />
	  </div>
      <div class="related">
		  <?php if ($tags) { ?>
		  <div class="tags"><b><?php echo $text_tags; ?></b>
			<?php for ($i = 0; $i < count($tags); $i++) { ?>
			<?php if ($i < (count($tags) - 1)) { ?>
			<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
			<?php } ?>
			<?php } ?>
		  </div>
		  <?php } ?>
		  <?php if ($figures) { ?>
		  <div class="figures"><b><?php echo $text_figures; ?></b>
			<?php for ($i = 0; $i < count($figures); $i++) { ?>
			<?php if ($i < (count($figures) - 1)) { ?>
			<a href="<?php echo $figures[$i]['href']; ?>"><?php echo $figures[$i]['figure']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $figures[$i]['href']; ?>"><?php echo $figures[$i]['figure']; ?></a>
			<?php } ?>
			<?php } ?>
		  </div>
		  <?php } ?>
		  <?php if ($spreads) { ?>
		  <div class="spreads"><b><?php echo $text_spreads; ?></b>
			<?php for ($i = 0; $i < count($spreads); $i++) { ?>
			<?php if ($i < (count($spreads) - 1)) { ?>
			<a href="<?php echo $spreads[$i]['href']; ?>"><?php echo $spreads[$i]['spread']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $spreads[$i]['href']; ?>"><?php echo $spreads[$i]['spread']; ?></a>
			<?php } ?>
			<?php } ?>
		  </div>
		  <?php } ?>
		  <?php if ($times) { ?>
		  <div class="times"><b><?php echo $text_times; ?></b>
			<?php for ($i = 0; $i < count($times); $i++) { ?>
			<?php if ($i < (count($times) - 1)) { ?>
			<a href="<?php echo $times[$i]['href']; ?>"><?php echo $times[$i]['time']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $times[$i]['href']; ?>"><?php echo $times[$i]['time']; ?></a>
			<?php } ?>
			<?php } ?>
		  </div>
		  <?php } ?>
		  <?php if ($locations) { ?>
		  <div class="locations"><b><?php echo $text_locations; ?></b>
			<?php for ($i = 0; $i < count($locations); $i++) { ?>
			<?php if ($i < (count($locations) - 1)) { ?>
			<a href="<?php echo $locations[$i]['href']; ?>"><?php echo $locations[$i]['location']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $locations[$i]['href']; ?>"><?php echo $locations[$i]['location']; ?></a>
			<?php } ?>
			<?php } ?>
		  </div>
		  <?php } ?>
	  </div>
    </div>
  </div>
  <div id="tabs" class="htabs"><a href="#tab-description"><?php echo $tab_description; ?></a>
    <?php if ($attribute_groups) { ?>
    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
    <?php } ?>
	<?php if ($cases) { ?>
    <a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($cases); ?>)</a>
    <?php } ?>
    <?php if ($excerpts) { ?>
    <a href="#tab-excerpt"><?php echo $tab_excerpt; ?> (<?php echo count($excerpts); ?>)</a>
    <?php } ?>
    <?php if ($downloads) { ?>
    <a href="#tab-download"><?php echo $tab_download; ?> (<?php echo count($downloads); ?>)</a>
    <?php } ?>
    <?php if ($review_status) { ?>
    <a href="#tab-review"><?php echo $tab_review; ?></a>
    <?php } ?>
  </div>
  <div id="tab-description" class="tab-content"><?php echo $description; ?></div>
  <?php if ($attribute_groups) { ?>
  <div id="tab-attribute" class="tab-content">
    <table class="attribute">
      <?php foreach ($attribute_groups as $attribute_group) { ?>
      <thead>
        <tr>
          <td colspan="2"><?php echo $attribute_group['name']; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
        <tr>
          <td><?php echo $attribute['name']; ?></td>
          <td><?php echo $attribute['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php } ?>
    </table>
  </div>
  <?php } ?>
  <?php if ($review_status) { ?>
  <div id="tab-review" class="tab-content">
    <div id="review"></div>
    <h2 id="review-title"><?php echo $text_write; ?></h2>
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="" />
    <br />
    <br />
    <b><?php echo $entry_review; ?></b>
    <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" />
    &nbsp;<span><?php echo $entry_good; ?></span><br />
    <br />
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="" size="16"/>
    <br />
    <img src="index.php?route=case/case/captcha" alt="" id="captcha" /><br />
    <br />
    <div class="buttons">
      <div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($cases) { ?>
  <div id="tab-related" class="tab-content">
    <div class="box-case">
      <table class='example' width="100%">
				<tr><td><select name="status">
                  <option value="1" selected="selected">内容维度</option>
                  <option value="0">时间维度</option>
                </select></tr></td>
                <tr>
                  <td style="text-align: center;">
				  <div style="width:930px;margin:0 auto;">
				  <canvas id='canvas1' width='920' height='540'></canvas>
				  </div></td>
                </tr>

                <div id="graph_container" style="width:900px;position: relative;min-height: 110px;">
                	<input type="hidden" id="filter_node" style="display: none;visibility: hidden;">
                	<div id="controller_tag" style="position: relative; height: 40px;">
                		<div class="control-pane" style="position: relative;float: left;z-index: 1000;margin-right: 10px;box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                			<a id="layout_controller" status="closed" href="javascript:void(0);" class="btn btn-danger" style="width: 230px;color: #428bca;"><h5>布局控制器<span class="glyphicon glyphicon-chevron-down"></span></h5></a>

                			<div id="layout_controller_div" style="display: none;margin: 10px;">
                				<div style="margin: 2px auto 10px 0px;font-size: 13px;text-align:left;">
                					<!--linLogMode-->对数模式

                                    <label>

                                     <input type="radio" name="linLogModeRadios1" value="true" checked>

                                      是

                                    </label>

                                    <label>

                                    <input type="radio" name="linLogModeRadios1" value="false">

                                     否

                                    </label>

                                    <input id="linLogModeInput" value="true" type="hidden" style="display: none;visibility: hidden;">
                			    </div>

                			    <div style="margin: 2px auto 10px 0px;font-size: 13px;text-align:left;">

                                    <!--outboundAttractionDistribution-->离心力分布模式

                                    <label>

                                    <input type="radio" name="outboundAttractionRadios1" value="true" checked>

                                     是

                                    </label>

                                    <label>

                                    <input type="radio" name="outboundAttractionRadios1" value="false">

                                     否

                                    </label>

                                    <input id="outboundAttractionInput" value="true" type="hidden" style="display: none;visibility: hidden;">
                                </div>

                                <div style="margin: 2px auto 10px 0px;font-size: 13px;text-align:left;">

                                    <!--adjustSizes-->调整尺寸模式

                                    <label>

                                    <input type="radio" name="adjustSizesRadios1" value="true" checked>

                                     是

                                    </label>

                                    <label>

                                    <input type="radio" name="adjustSizesRadios1" value="false">

                                    否

                                    </label>

                                    <input id="adjustSizesInput" value="true" type="hidden" style="display: none;visibility: hidden;">
                                </div>

                                <div style="margin: 2px auto 10px 0px;font-size: 13px;text-align:left;">

                                    <!--strongGravityMode-->强引力模式

                                    <label>

                                    <input type="radio" name="strongGravityModeRadios1" value="true" checked>

                                     是

                                    </label>

                                    <label>

                                    <input type="radio" name="strongGravityModeRadios1" value="false">

                                     否

                                    </label>

                                    <input id="strongGravityModeInput" value="true" type="hidden" style="display: none;visibility: hidden;">
                                </div>

                                <div style="margin: 2px auto 10px 0px;font-size: 13px;text-align:left;">

                                    <!--edgeWeightInfluence-->边权重影响&nbsp;&nbsp;<span id="edgeWeightInfluence_span">1</span><br>

                                    <input id="edgeWeightInfluence_input" type="range" min="0" style="width: 130px;display: inline;" value="1" onclick="change_edgeWeightInfluence();">
                                </div>

                                <div style="margin: 2px auto 10px 0px;font-size: 13px;text-align:left;">

                                    <!--scalingRatio-->缩放比例&nbsp;&nbsp;<span id="scalingRatio_span">1</span><br>

                                    <input id="scalingRatio_input" type="range" min="0" style="width: 130px;display: inline;" value="1" onclick="change_scalingRatio();">

                                </div>

                                <div style="margin: 2px auto 10px 0px;font-size: 13px;text-align:left;">

                                    <!--gravity-->引力&nbsp;&nbsp;<span id="gravity_span">10</span><br>

                                    <input id="gravity_input" type="range" min="0" style="width: 130px;display: inline;" value="10" onclick="change_gravity();"> 

                                </div>

                                <div style="margin: 2px auto 10px 0px;font-size: 13px;text-align:left;">

                                    <!--slowdown-->收敛速度&nbsp;&nbsp;<span id="slowdown_span">2</span><br>

                                    <input id="slowdown_input" type="range" min="0" style="width: 130px;display: inline;" value="2" onclick="change_slowdown();"> 

                                </div>

                                <span class="line" style="clear:both;display:block;width:100%;margin:0;padding:12px 0 0 0;border-bottom:1px solid #aac789;background:transparent;"></span>

                                <span>

                                    <a style="cursor:pointer;" id="refresh_layout" class="button button-rounded button-flat-primary"><i class="glyphicon glyphicon-refresh icons"></i>刷新布局</a>

                                    <a style="cursor:pointer;" id="pause_layout" class="button button-rounded button-flat-primary"><i class="glyphicon glyphicon-pause icons"></i>暂停布局</a>

                                    <a style="cursor:pointer;" id="stop_layout" class="button button-rounded button-flat-primary"><i class="glyphicon glyphicon-stop icons"></i>停止布局</a>

                                </span>
                			</div>
                		</div>

                		<div class="control-pane" style="position: relative;float: left;z-index: 1000;margin-right: 10px;box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                			<a id="filter_controller" status="closed" href="javascript:void(0);" class="btn btn-danger" style="width: 181px;color: #428bca;"><h5>过滤器<span class="glyphicon glyphicon-chevron-down"></span></h5></a>

                			<div id="filter_controller_div" style="display: none;margin: 10px;text-align:left;">
                				<div>

                                    指标类别

                                    <select id="zhibiao-category">

                                    <option value="degree" selected>度</option>

                                    <option value="pagerank">PR值</option>

                                    </select>

                                </div>

                                <div id="min_degree_container" style="text-align:left;">

                                    最小度&nbsp;&nbsp;<span id="min-degree-val">0</span><br>

                                    <input id="min-degree" type="range" min="0" value="0"> <span id="max-degree-value">0</span>

                                </div>

                                <div id="min_pagerank_container" class="hidden" style="text-align:left;">
                                  最小PR值&nbsp;&nbsp;<span id="min-pagerank-val">0</span>e-8<br>
                                  <input id="min-pagerank" type="range" min="0" value="0"> <span id="max-pagerank-value">0</span>e-8
                                </div>

                                <div style="text-align:left;">

                                    节点类别

                                    <select id="node-category">

                                        <option value="" selected>所有类别</option>

                                    </select>

                                </div>

                                <span class="line" style="clear: both;display: block;width: 100%;margin: 0;padding: 12px 0 0 0;border-bottom: 1px solid #aac789;background: transparent;"></span>

                                <div>

                                    <button id="reset-btn" style="text-align:left;">重置过滤器</button>

                                </div>
                			</div> 

                		</div>

                		<div class="control-pane" style="position: relative;float: left;z-index: 1000;margin-right: 10px;box-shadow: 0 2px 6px rgba(0,0,0,0.3);">

                            <a href="javascript:void(0);" onclick="show_network();" class="btn btn-danger"><h5>生成并查看网络<span class="glyphicon glyphicon-arrow-down"></span></h5></a>

                        </div>

                        <div class="control-pane" style="position: relative;float: left;z-index: 1000;margin-right: 10px;box-shadow: 0 2px 6px rgba(0,0,0,0.3);">

                            <a href="javascript:void(0);" onclick="download();" class="btn btn-danger" ><h5>下载图片<span class="glyphicon glyphicon-save"></span></h5></a>

                        </div>
                	</div>

                	<div id="network" class="collapse out" style="position: relative;">
                		<div class="sigma-expand" id="sigma-graph" style="position: absolute;margin: 0px auto;height: 620px;width: 686px;float: left;"></div>

                		<div id="control-pane-rightdown" style="right: 2px;margin-right:10px; position: absolute;float: left;width: 304px;background-color: rgb(249, 247, 237);box-shadow: 0 2px 6px rgba(0,0,0,0.3);">

                             <h4 class="underline" style="color: #437356;background: #f4f0e4;margin: 0;border-radius: 2px;padding: 8px 12px;font-weight: 700;">节点信息</h4>

                             <div style="margin:10px;text-align:left;">

                                用户昵称: <span id="nickname"></span>&nbsp;&nbsp;

                             </div>

                             <div style="margin:10px;text-align:left;">

                                用户所在地: <span id="location"></span>

                             </div>

                             <div style="margin:10px;text-align:left;">

                                用户PR值: <span id="pagerank"></span>

                             </div>

                             <div style="margin:10px;float:left;">
                                <span id="community"></span>
                                <span><a id="user_weibo" class="block" href="#network_weibo_div" style="color: #428bca;">微博</span>
                                <span><a id="neighbourhood_detail_a" class="block" href="#network_uid_neighbor1" style="color: #428bca;">邻居</a></span>
                                <span><a id="community_detail_a" class="block" href="#network_uid_community1" style="color: #428bca;">社团</a></span>
                             </div>

                         </div>


                	</div>

                 </div>

      </table>
    </div>
  </div>
  <?php } ?> 
  <?php if ($excerpts) { ?>
  <div id="tab-excerpt" class="tab-content">
    <div>
      <?php foreach ($excerpts as $excerpt) { ?>
        <div class="content"><b><?php echo nl2br($excerpt['contenttype']); ?></b>：<?php echo nl2br($excerpt['content']); ?></div>
      <?php } ?>
    </div>
  </div>  
  <?php } ?>  
  <?php if ($downloads) { ?>
  <div id="tab-download" class="tab-content">
    <div>	
		<?php if ($downloads) { ?>
		<?php for ($i = 0; $i < count($downloads); $i++) { ?>
		<a href="<?php echo $downloads[$i]['href']; ?>"><?php echo $downloads[$i]['name']; ?></a><br><br>
		<?php } ?>
		<?php } ?>
    </div>
  </div>  
  <?php } ?>  
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox"
	});
});
//--></script> 
<script type="text/javascript"><!--

$('select[name="profile_id"], input[name="quantity"]').change(function(){
    $.ajax({
		url: 'index.php?route=case/case/getRecurringDescription',
		type: 'post',
		data: $('input[name="case_id"], input[name="quantity"], select[name="profile_id"]'),
		dataType: 'json',
        beforeSend: function() {
            $('#profile-description').html('');
        },
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
            
			if (json['success']) {
                $('#profile-description').html(json['success']);
			}	
		}
	});
});
    
$('#button-cart').bind('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('.case-info input[type=\'text\'], .case-info input[type=\'hidden\'], .case-info input[type=\'radio\']:checked, .case-info input[type=\'checkbox\']:checked, .case-info select, .case-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
					}
				}
                
                if (json['error']['profile']) {
                    $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                }
			} 
			
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
				$('.success').fadeIn('slow');
					
				$('#cart-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}	
		}
	});
});
//--></script>
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['case_option_id']; ?>', {
	action: 'index.php?route=case/case/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['case_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		$('#button-option-<?php echo $option['case_option_id']; ?>').attr('disabled', true);
	},
	onComplete: function(file, json) {
		$('#button-option-<?php echo $option['case_option_id']; ?>').attr('disabled', false);
		
		$('.error').remove();
		
		if (json['success']) {
			alert(json['success']);
			
			$('input[name=\'option[<?php echo $option['case_option_id']; ?>]\']').attr('value', json['file']);
		}
		
		if (json['error']) {
			$('#option-<?php echo $option['case_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
		}
		
		$('.loading').remove();	
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').fadeOut('slow');
		
	$('#review').load(this.href);
	
	$('#review').fadeIn('slow');
	
	return false;
});			

$('#review').load('index.php?route=case/case/review&case_id=<?php echo $case_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=case/case/write&case_id=<?php echo $case_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
			}
			
			if (data['success']) {
				$('#review-title').after('<div class="success">' + data['success'] + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	if ($.browser.msie && $.browser.version == 6) {
		$('.date, .datetime, .time').bgIframe();
	}

	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').timepicker({timeFormat: 'h:m'});
	showDemo();
});
//--></script> 
    <script id='demoScript'>

      var showDemo = function () {
        var cx1 = new CanvasXpress('canvas1',<?php echo $json; ?>,
          {'backgroundGradient1Color': 'rgb(112,179,222)',
          'backgroundGradient2Color': 'rgb(226,236,248)',
          'colorNodeBy': 'group',
          'gradient': true,
          'graphType': 'Network',
          'indicatorCenter': 'rainbow',
          'nodeFontColor': 'rgb(29,34,43)',
          'showAnimation': true}
        );
      }

      var showCode = function (e, id) {
        var cx = CanvasXpress.getObject(id)
        cx.stopEvent(e);
        cx.cancelEvent(e);
        cx.updateCodeDiv(10000);
        return false;
      }
    </script>

    <script>
        $("#layout_controller").click(function(){
            if($("#layout_controller").attr('status') == 'closed'){
                $("#layout_controller_div").css("display", "block");
                $("#layout_controller").attr('status', 'opened')
                $("#layout_controller").html("<h5>布局控制器<span class=\"glyphicon glyphicon-chevron-up\"></span></h5>");
            }
            else{
                $("#layout_controller_div").css("display", "none");
                $("#layout_controller").attr('status', 'closed');
                $("#layout_controller").html("<h5>布局控制器<span class=\"glyphicon glyphicon-chevron-down\"></span></h5>");
            }
        });
        $("#filter_controller").click(function(){
            if($("#filter_controller").attr('status') == 'closed'){
                $("#filter_controller_div").css("display", "block");
                $("#filter_controller").attr('status', 'opened')
                $("#filter_controller").html("<h5>过滤器<span class=\"glyphicon glyphicon-chevron-up\"></span></h5>");
            }
            else{
                $("#filter_controller_div").css("display", "none");
                $("#filter_controller").attr('status', 'closed');
                $("#filter_controller").html("<h5>过滤器<span class=\"glyphicon glyphicon-chevron-down\"></span></h5>");
            }
        });

    </script



<?php echo $footer; ?>
