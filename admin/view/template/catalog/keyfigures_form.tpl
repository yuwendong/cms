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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	  <div id="tab-data">
          <table class="form">
            <tr>
              <td><?php echo $entry_name; ?></td>
              <td><input type="text" name="name" value="<?php echo $name; ?>" /></td>
            </tr>      
				  <input type="hidden" name="keyfigures_store[]" value='0'/>      
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
            </tr>
          </table>
        </div>
        <div id="tab-general">
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                <td><input type="text" name="keyfigures_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($keyfigures_description[$language['language_id']]) ? $keyfigures_description[$language['language_id']]['title'] : ''; ?>" />
                  <?php if (isset($error_title[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_description; ?></td>
                <td><textarea name="keyfigures_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($keyfigures_description[$language['language_id']]) ? $keyfigures_description[$language['language_id']]['description'] : ''; ?></textarea>
                  <?php if (isset($error_description[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
                  <?php } ?></td>
              </tr>
            </table>
          </div>
          <?php } ?>
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
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
//--></script> 
<?php echo $footer; ?>