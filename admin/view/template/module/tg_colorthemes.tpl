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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
  
   <div id="tabs" class="htabs"><a tab="#tab_general"><?php echo $tab_gen; ?></a></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
      
      
      <div id="tab_general">
      <table class="form">
 
 
 		
 
 
         <tr>
          <td><?php echo $entry_color; ?></td>
          <td><select name="tg_colorthemes_default_color">
              
              <?php if (isset($tg_colorthemes_default_color)) {
              $selected = "selected";
              ?>
											
              
              <option value="blue_stylesheet" <?php if($tg_colorthemes_default_color=='blue_stylesheet'){echo $selected;} ?>>Blue Color</option>
              <option value="teal_stylesheet" <?php if($tg_colorthemes_default_color=='teal_stylesheet'){echo $selected;} ?>>Teal Color</option>
              <option value="gray_stylesheet" <?php if($tg_colorthemes_default_color=='gray_stylesheet'){echo $selected;} ?>>Gray Color</option>
              <option value="red_stylesheet" <?php if($tg_colorthemes_default_color=='red_stylesheet'){echo $selected;} ?>>Red Color</option>
              <option value="purple_stylesheet" <?php if($tg_colorthemes_default_color=='purple_stylesheet'){echo $selected;} ?>>Purple Color</option>
              <option value="pink_stylesheet" <?php if($tg_colorthemes_default_color=='pink_stylesheet'){echo $selected;} ?>>Pink Color</option>
              <option value="orange_stylesheet" <?php if($tg_colorthemes_default_color=='orange_stylesheet'){echo $selected;} ?>>Orange Color</option>
              <option value="navy_stylesheet" <?php if($tg_colorthemes_default_color=='navy_stylesheet'){echo $selected;} ?>>Navy Color</option>
              <option value="light_stylesheet" <?php if($tg_colorthemes_default_color=='light_stylesheet'){echo $selected;} ?>>Light Color</option>
              <option value="light_blue_stylesheet" <?php if($tg_colorthemes_default_color=='light_blue_stylesheet'){echo $selected;} ?>>Light Blue Color</option>
              <option value="green_stylesheet" <?php if($tg_colorthemes_default_color=='green_stylesheet'){echo $selected;} ?>>Green Color</option>
              <option value="dark_stylesheet" <?php if($tg_colorthemes_default_color=='dark_stylesheet'){echo $selected;} ?>>Dark Color</option>
              <option value="dark_purple_stylesheet" <?php if($tg_colorthemes_default_color=='dark_purple_stylesheet'){echo $selected;} ?>>Dark Purple Color</option>
              <option value="brown_stylesheet" <?php if($tg_colorthemes_default_color=='brown_stylesheet'){echo $selected;} ?>>Brown Color</option>
              <option value="white_stylesheet" <?php if($tg_colorthemes_default_color=='white_stylesheet'){echo $selected;} ?>>White Color</option>
              <option value="black_stylesheet" <?php if($tg_colorthemes_default_color=='black_stylesheet'){echo $selected;} ?>>Black Color</option>
              <?php } else { ?>
              <option value="blue_stylesheet">Blue Color</option>
              <option value="teal_stylesheet">Teal Color</option>
              <option value="gray_stylesheet">Gray Color</option>
              <option value="red_stylesheet">Red Color</option>
              <option value="purple_stylesheet">Purple Color</option>
              <option value="pink_stylesheet">Pink Color</option>
              <option value="orange_stylesheet">Orange Color</option>
      		  <option value="navy_stylesheet">Navy Color</option>
        	  <option value="light_stylesheet">Light Color</option>
          	  <option value="light_blue_stylesheet">Light Blue Color</option>
              <option value="green_stylesheet">Green Color</option>
              <option value="dark_stylesheet">Dark Color</option>
              <option value="dark_purple_stylesheet">Dark Purple Color</option>
              <option value="brown_stylesheet">Brown Color</option>
              <option value="white_stylesheet">White Color</option>
              <option value="black_stylesheet">Black Color</option>
              <?php } ?>
           </select></td>
        </tr>

        <tr>
            <td>Color Themes</td>
            
         <td><input type="checkbox" value="1" name="tg_colorthemes_status"<?php if($tg_colorthemes_status == '1') echo ' checked="checked"';?> /> Show</td>
        </tr>

     
        
       </table>
       </div>    
    </form>
  </div>
</div>

<script type="text/javascript"><!--
$.tabs('#tabs a'); 
$.tabs('#languages a');
//--></script>
<?php echo $footer; ?>
 
 
 <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--

//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript">

<?php echo $footer; ?>