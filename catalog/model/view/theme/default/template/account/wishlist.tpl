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
  <div class="wishlist-info">
    <table>
      <thead>
        <tr>
          <td class="image"><?php echo $column_image; ?></td>
          <td class="name"><?php echo $column_name; ?></td>
          <td class="model"><?php echo $column_model; ?></td>
          <td class="stock"><?php echo $column_stock; ?></td>
          <td class="price"><?php echo $column_price; ?></td>
          <td class="action"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <?php foreach ($cases as $case) { ?>
      <tbody id="wishlist-row<?php echo $case['case_id']; ?>">
        <tr>
          <td class="image"><?php if ($case['thumb']) { ?>
            <a href="<?php echo $case['href']; ?>"><img src="<?php echo $case['thumb']; ?>" alt="<?php echo $case['name']; ?>" title="<?php echo $case['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name"><a href="<?php echo $case['href']; ?>"><?php echo $case['name']; ?></a></td>
          <td class="model"><?php echo $case['model']; ?></td>
          <td class="stock"><?php echo $case['stock']; ?></td>
          <td class="price"><?php if ($case['price']) { ?>
            <div class="price">
              <?php if (!$case['special']) { ?>
              <?php echo $case['price']; ?>
              <?php } else { ?>
              <s><?php echo $case['price']; ?></s> <b><?php echo $case['special']; ?></b>
              <?php } ?>
            </div>
            <?php } ?></td>
          <td class="action"><img src="catalog/view/theme/default/image/cart-add.png" alt="<?php echo $button_cart; ?>" title="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $case['case_id']; ?>');" />&nbsp;&nbsp;<a href="<?php echo $case['remove']; ?>"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td>
        </tr>
      </tbody>
      <?php } ?>
    </table>
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