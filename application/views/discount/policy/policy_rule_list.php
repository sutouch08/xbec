<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped table-bordered table-narrow border-1" style="min-width:1240px;">
      <thead>
        <tr>
          <th colspan="7" class="middle text-center">Details</th>
          <th colspan="6" class="middle text-center">Conditions</th>
        </tr>
        <tr class="font-size-10">
          <th class="fix-width-40 middle text-center">
            <label>
              <input type="checkbox" class="ace" id="rm-chk-all" onchange="toggleRmCheckAll()" />
              <span class="lbl"></span>
            </label>
          </th>
          <th class="fix-width-40 middle text-center">#</th>
          <th class="fix-width-100 middle text-center">Code</th>
          <th class="min-width-200 middle text-center">Description</th>
          <th class="fix-width-100 middle text-center">Methods</th>
          <th class="fix-width-120 middle text-center">Discount</th>
          <th class="fix-width-100 middle text-center">Premium Qty.</th>
          <th class="fix-width-80 text-center">Customer</th>
          <th class="fix-width-80 text-center">Product</th>
          <th class="fix-width-80 text-center">Channels</th>
          <th class="fix-width-80 text-center">Payment</th>
          <th class="fix-width-80 text-center">Min Qty.</th>
          <th class="fix-width-100 text-center">Min Amount.</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rules)) : ?>
          <?php $no = 1; ?>
          <?php foreach ($rules as $rs) : ?>
            <tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
              <td class="middle text-center">
                <label>
                  <input type="checkbox" class="ace rm-chk" value="<?php echo $rs->id; ?>" />
                  <span class="lbl"></span>
                </label>
              </td>
              <td class="middle text-center"><?php echo $no; ?></td>
              <td class="middle text-center">
                <a style="color:inherit" href="javascript:viewRuleDetail(<?php echo $rs->id; ?>)" ><?php echo $rs->code; ?></a>
              </td>
              <td class="middle"><?php echo $rs->name; ?></td>
              <td class="middle text-center"><?php echo ($rs->type == 'N' ? 'Net Price' : ($rs->type == 'P' ? 'Percentage' : 'Premium')); ?></td>
              <td class="middle text-center"><?php echo discount_label($rs->type, $rs->price, $rs->disc1, $rs->disc2, $rs->disc3, $rs->disc4, $rs->disc5); ?></td>
              <td class="middle text-center"><?php echo $rs->freeQty; ?></td>
              <td class="middle text-center"><?php echo ($rs->all_customer == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
              <td class="middle text-center"><?php echo ($rs->all_product == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
              <td class="middle text-center"><?php echo ($rs->all_channels == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
              <td class="middle text-center"><?php echo ($rs->all_payment == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
              <td class="middle text-center"><?php echo (empty($rs->minQty) ? "No" : number($rs->minQty)); ?></td>
              <td class="middle text-center"><?php echo (empty($rs->minAmount) ? "No" : number($rs->minAmount, 2)); ?></td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>

        <?php else : ?>
          <tr>
            <td id="no-disc-row" colspan="13" class="text-center">--- No Discount Rule ---</td>
          </tr>

        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>