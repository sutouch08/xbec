<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <button type="button" class="btn btn-white btn-info" onclick="addRow()">Add Row</button>
    <button type="button" class="btn btn-white btn-warning" onclick="removeRow()">Delete Row</button>
    <button type="button" class="btn btn-white btn-success" onclick="getImportFile()">Import Excel</button>
    <button type="button" class="btn btn-white btn-purple" onclick="getTemplateFile()">Download Template</button>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <p class="pull-right" id="free-box"></p>
  </div>
  <div class="hide" id="free-temp"></div>
  <div class="divider-hidden">

  </div>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-1 table-responsive" style="height:400px; overflow:auto; padding:0px; margin-left:5px;">
    <table class="table table-bordered border-1 tableFixHead" style="min-width:2170px;">
      <thead>
        <tr class="font-size-10 freez">
          <th class="fix-width-40 middle text-center fix-no fix-header">#</th>
          <th class="fix-width-40 middle text-center fix-chk fix-header"></th>
          <th class="fix-width-60 middle text-center fix-img fix-header">Image</th>
          <th class="fix-width-150 middle text-center fix-item fix-header">Item Code</th>
          <th class="fix-width-250 middle text-center fix-desc fix-header">Description.</th>
          <th class="fix-width-100 middle text-center">Warehouse</th>
          <th class="fix-width-80 middle text-center">In Stock</th>
          <th class="fix-width-100 middle text-center">Quota No.</th>
          <th class="fix-width-80 middle text-center">Quota</th>
          <th class="fix-width-80 middle text-center">Commited</th>
          <th class="fix-width-80 middle text-center">Available</th>
          <th class="fix-width-80 middle text-center">Master pack</th>
          <th class="fix-width-100 middle text-center">Quantity</th>
          <th class="fix-width-100 middle text-center">Uom</th>
          <th class="fix-width-100 middle text-center">Std Price</th>
          <th class="fix-width-100 middle text-center">Price</th>
          <th class="fix-width-150 middle text-center">Discount(%)</th>
          <th class="fix-width-80 middle text-center">Tax Code</th>
          <th class="fix-width-120 middle text-center">Price after discount</th>
          <th class="min-width-150 middle text-center">Amount before tax</th>
          <th class="fix-width-100 middle">Disc Rule No.</th>
          <th class="fix-width-60 middle text-center">Premium</th>
        </tr>
      </thead>
      <tbody id="details-template">
        <?php $rows = 5; ?>
        <?php $no = 1; ?>
        <?php if (!empty($details)) : ?>
          <?php $parent = array(); ?>
          <?php $disabled = ""; ?>
          <?php foreach ($details as $rs) : ?>
            <?php
            $uid = genUid(8);
            $disabled = $rs->is_free == 1 ? 'disabled' : '';
            $freeRow = $rs->is_free == 1 ? 'free-row' : '';
            $can_change = $rs->allow_change_discount == 0 ? 'disabled' : '';
            ?>

            <tr id="row-<?php echo $uid; ?>" class="<?php echo $freeRow; ?>">
              <input type="hidden" id="product-id-<?php echo $uid; ?>" value="<?php echo $rs->product_id; ?>" />
              <input type="hidden" id="cost-<?php echo $uid; ?>" value="<?php echo $rs->Cost; ?>" />
              <input type="hidden" id="line-cost-<?php echo $uid; ?>" value="<?php echo $rs->totalCost; ?>" />
              <input type="hidden" id="stdPrice-<?php echo $uid; ?>" value="<?php echo $rs->StdPrice; ?>" />
              <input type="hidden" id="price-<?php echo $uid; ?>" value="<?php echo $rs->Price; ?>" />
              <input type="hidden" id="sellPrice-<?php echo $uid; ?>" value="<?php echo $rs->SellPrice; ?>" />
              <input type="hidden" id="sysSellPrice-<?php echo $uid; ?>" value="<?php echo $rs->sysSellPrice; ?>" />
              <input type="hidden" class="line-num" id="line-num-<?php echo $uid; ?>" value="<?php echo $uid; ?>" />
              <input type="hidden" id="disc-amount-<?php echo $uid; ?>" value="<?php echo $rs->discAmount; ?>" />
              <input type="hidden" id="totalDiscPercent-<?php echo $uid; ?>" value="<?php echo $rs->DiscPrcnt; ?>" />
              <input type="hidden" id="line-disc-amount-<?php echo $uid; ?>" value="<?php echo $rs->totalDiscAmount; ?>" />
              <input type="hidden" id="line-total-<?php echo $uid; ?>" value="<?php echo $rs->LineTotal; ?>" />
              <input type="hidden" id="vat-rate-<?php echo $uid; ?>" value="<?php echo $rs->VatRate; ?>" />
              <input type="hidden" id="vat-amount-<?php echo $uid; ?>" value="<?php echo $rs->VatAmount; ?>" />
              <input type="hidden" id="vat-total-<?php echo $uid; ?>" value="<?php echo $rs->totalVatAmount; ?>" />
              <input type="hidden" id="sys-disc-label-<?php echo $uid; ?>" value="<?php echo $rs->sysDiscLabel; ?>" />
              <input type="hidden" id="uom-code-<?php echo $uid; ?>" value="<?php echo $rs->UomCode; ?>" />
              <input type="hidden" class="disc-diff" id="disc-diff-<?php echo $uid; ?>" value="<?php echo $rs->discDiff; ?>" />
              <input type="hidden" id="rule-id-<?php echo $uid; ?>" value="<?php echo $rs->rule_id; ?>" />
              <input type="hidden" class="policy" id="policy-id-<?php echo $uid; ?>" data-code="<?php echo $rs->policy_code; ?>" data-name="<?php echo $rs->policy_name; ?>" data-uid="<?php echo $uid; ?>" value="<?php echo $rs->policy_id; ?>" />
              <input type="hidden" class="disc-error" id="disc-error-<?php echo $uid; ?>" value="0" data-id="<?php echo $uid; ?>" />
              <input type="hidden" class="is-free" id="is-free-<?php echo $uid; ?>"
                value="<?php echo $rs->is_free; ?>" data-id="<?php echo $uid; ?>"
                data-parent="<?php echo $rs->parent_uid; ?>" data-parentrow="<?php echo $rs->rule_id; ?>" />
              <input type="hidden" id="<?php echo $rs->uid; ?>" data-id="<?php echo $uid; ?>" value="<?php echo $uid; ?>" />
              <input type="hidden" id="disc-type-<?php echo $uid; ?>" value="<?php echo $rs->discType; ?>" />
              <input type="hidden" id="count-stock-<?php echo $uid; ?>" value="<?php echo $rs->count_stock; ?>" />
              <input type="hidden" id="allow-change-discount-<?php echo $uid; ?>" value="<?php echo $rs->allow_change_discount; ?>" />

              <td class="middle text-center fix-no no handle" id="no-<?php echo $uid; ?>" scope="row"><?php echo $no; ?></td>
              <td class="middle text-center fix-chk" scope="row">
                <input type="checkbox" class="ace del-chk" value="<?php echo $uid; ?>" />
                <span class="lbl"></span>
              </td>
              <td class="middle text-center fix-img" scope="row" id="img-<?php echo $uid; ?>">
                <img src="<?php echo $rs->image; ?>" width="40" height="40" />
              </td>
              <td class="middle fix-item" scope="row">
                <input type="text" class="form-control input-sm item-code" data-id="<?php echo $uid; ?>" id="itemCode-<?php echo $uid; ?>" value="<?php echo escape_str($rs->ItemCode); ?>" <?php echo $disabled; ?> />
              </td>
              <td class="middle fix-desc" scope="row">
                <input type="text" class="form-control input-sm item-name" data-id="<?php echo $uid; ?>" id="itemName-<?php echo $uid; ?>" value="<?php echo escape_str($rs->ItemName); ?>" <?php echo $disabled; ?> />
              </td>

              <td class="middle">
                <select class="form-control input-sm whs" data-id="<?php echo $uid; ?>" id="whs-<?php echo $uid; ?>" onchange="getStock('<?php echo $uid; ?>')">
                  <option value=""></option>
                  <?php echo select_order_warehouse($whs, $rs->WhsCode); ?>
                </select>
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right" id="instock-<?php echo $uid; ?>" value="<?php echo number($rs->instock); ?>" disabled />
              </td>

              <td class="middle">
                <select class="form-control input-sm quota" data-id="<?php echo $uid; ?>" id="quota-<?php echo $uid; ?>" onchange="getStock('<?php echo $uid; ?>')">
                  <option value=""></option>
                  <?php echo select_order_quota($qn, $rs->QuotaNo); ?>
                </select>
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right" id="team-<?php echo $uid; ?>" value="<?php echo number($rs->team); ?>" disabled />
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right" id="commit-<?php echo $uid; ?>" value="<?php echo number($rs->commit); ?>" disabled />
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right" id="available-<?php echo $uid; ?>" value="<?php echo number($rs->available); ?>" disabled />
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right" id="master-pack-<?php echo $uid; ?>" value="<?php echo $rs->master_pack; ?>" disabled />
              </td>

              <td class="middle">
                <input type="number" class="form-control input-sm text-right line-qty"
                  data-id="<?php echo $uid; ?>" id="line-qty-<?php echo $uid; ?>"
                  value="<?php echo $rs->Qty; ?>" onkeyup="recalAmount('<?php echo $uid; ?>')" <?php echo $disabled; ?> />
              </td>
              <td class="middle">
                <input type="text" class="form-control input-sm text-center" id="uom-<?php echo $uid; ?>" value="<?php echo $rs->uom_name; ?>" disabled />
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right number" id="stdPrice-label-<?php echo $uid; ?>" value="<?php echo number($rs->StdPrice, 2); ?>" readonly disabled />
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right number" id="price-label-<?php echo $uid; ?>"
                  value="<?php echo number($rs->Price, 2); ?>" onchange="recalAmount('<?php echo $uid; ?>')" <?php echo $rs->count_stock == 1 ? 'disabled' : ''; ?> />
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right r" id="disc-label-<?php echo $uid; ?>"
                  value="<?php echo $rs->discLabel; ?>"
                  onchange="recalDiscount('<?php echo $uid; ?>')"
                  <?php echo $disabled; ?>
                  <?php echo $can_change; ?> />
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-center" id="vat-code-<?php echo $uid; ?>" value="<?php echo $rs->VatGroup; ?>" disabled />
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right" id="sell-price-<?php echo $uid; ?>" value="<?php echo number($rs->SellPrice, 4); ?>" readonly disabled>
              </td>

              <td class="middle">
                <input type="text" class="form-control input-sm text-right number input-amount" id="total-label-<?php echo $uid; ?>" value="<?php echo number($rs->LineTotal, 2); ?>" readonly disabled />
              </td>
              <td class="middle">
                <input type="text" class="form-control input-sm text-center" id="disc-rule-<?php echo $uid; ?>" value="<?php echo $rs->rule_code; ?>" disabled />
              </td>
              <td class="middle text-center"><?php echo $rs->is_free == 1 ? 'Y' : ''; ?></td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <input type="hidden" id="row-no" value="<?php echo $uid; ?>" />
  </div>  
  <input type="file" class="hide" name="uploadFile" id="uploadFile" accept=".xlsx" />
</div>
<hr class="padding-5" />

<?php $this->load->view('sales_order/sales_order_detail_template'); ?>