<?php if (!isset($redirect)) { ?>
<div class="checkout-case">
  <table>
    <thead>
      <tr>
        <td class="name"><?php echo $column_name; ?></td>
        <td class="model"><?php echo $column_model; ?></td>
        <td class="quantity"><?php echo $column_quantity; ?></td>
        <td class="price"><?php echo $column_price; ?></td>
        <td class="total"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cases as $case) { ?>  
      <?php if($case['recurring']): ?>
        <tr>
            <td colspan="6" style="border:none;"><image src="catalog/view/theme/default/image/reorder.png" alt="" title="" style="float:left;" /><span style="float:left;line-height:18px; margin-left:10px;"> 
                <strong><?php echo $text_recurring_item ?></strong>
                <?php echo $case['profile_description'] ?>
            </td>
        </tr>
      <?php endif; ?>
      <tr>
        <td class="name"><a href="<?php echo $case['href']; ?>"><?php echo $case['name']; ?></a>
          <?php foreach ($case['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?>
          <?php if($case['recurring']): ?>
          <br />
          &nbsp;<small><?php echo $text_payment_profile ?>: <?php echo $case['profile_name'] ?></small>
          <?php endif; ?>
        </td>
        <td class="model"><?php echo $case['model']; ?></td>
        <td class="quantity"><?php echo $case['quantity']; ?></td>
        <td class="price"><?php echo $case['price']; ?></td>
        <td class="total"><?php echo $case['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="name"><?php echo $voucher['description']; ?></td>
        <td class="model"></td>
        <td class="quantity">1</td>
        <td class="price"><?php echo $voucher['amount']; ?></td>
        <td class="total"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="price"><b><?php echo $total['title']; ?>:</b></td>
        <td class="total"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<div class="payment"><?php echo $payment; ?></div>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script> 
<?php } ?>