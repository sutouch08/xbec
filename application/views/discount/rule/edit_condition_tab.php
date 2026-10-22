<?php $c_active = $rule->all_customer ? 'disabled' : ''; ?>
<div class="form-horizontal <?php echo $rule->type == 'P' ? '' : 'hide'; ?>" id="condition-discount">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Discounts</h4>
    </label>
  </div>
  <div class="form-group">
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">
      <label class="display-block not-show">&nbsp;</label>
      <label class="">Discount (%)</label>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 1</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="1" id="disc-1" value="<?php echo $rule->disc1; ?>" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 2</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="2" id="disc-2" value="<?php echo $rule->disc2; ?>" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 3</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="3" id="disc-3" value="<?php echo $rule->disc3; ?>" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 4</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="4" id="disc-4" value="<?php echo $rule->disc4; ?>" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 5</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="5" id="disc-5" value="<?php echo $rule->disc5; ?>" />
    </div>
  </div>
</div><!-- form-horizontal -->

<div class="form-horizontal <?php echo $rule->type == 'F' ? '' : 'hide'; ?>" id="condition-premium">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Premiums</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Premium</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="premium-qty" value="<?php echo $rule->freeQty; ?>" />
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> ชิ้น จากรายการต่อไปนี้ </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Premium SKU</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
      <input type="text" class="form-control input-sm" id="premium-sku" placeholder="Specify the product code to add premium items." />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
      <input type="file" class="hide" name="premiumFile" id="premium-file" accept=".xlsx" />
      <button type="button" class="btn btn-xs btn-success btn-white btn-block" id="btn-import-premium" onclick="getPremiumFile()"><i class="fa fa-plus"></i> Import Excel</button>
    </div>
    <div class="col-lg-7-harf col-lg-offset-1-harf col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-2 col-xs-12" style="max-height:300px; overflow-y:auto; margin-top:10px;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:640px; margin-bottom:0px;">
        <thead>
          <tr>
            <th class="fix-width-40 middle text-center">
              <label>
                <input type="checkbox" class="ace" id="chk-all-premium" onchange="toggleAllPremium(this)" />
                <span class="lbl"></span>
              </label>
            </th>
            <th class="fix-width-40 middle text-center">#</th>
            <th class="fix-width-100 middle">Item Code</th>
            <th class="min-width-250 middle">Description</th>
            <th class="fix-width-80 middle">Std Price</th>
            <th class="fix-width-90 middle">Premium Price</th>
            <th class="fix-width-40 middle text-right">
              <button type="button" class="btn btn-minier btn-danger" onclick="removePremiumChecked()"><i class="fa fa-trash"></i></button>
            </th>
          </tr>
        </thead>
        <tbody id="premium-table">
          <?php if (!empty($premiums)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($premiums as $pd) : ?>
              <tr id="premium-row-<?php echo $pd->product_id; ?>">
                <td class="middle text-center">
                  <label>
                    <input type="checkbox" class="ace chk-premium" data-id="<?php echo $pd->product_id; ?>" />
                    <span class="lbl"></span>
                  </label>
                </td>
                <td class="middle text-center premium-no"><?php echo $no; ?></td>
                <td class="middle"><?php echo $pd->code; ?></td>
                <td class="middle"><?php echo $pd->name; ?></td>
                <td class="middle text-right"><?php echo number($pd->price, 2); ?></td>
                <td class="middle">
                  <input type="number" class="form-control input-xs text-right premium-price"
                    data-id="<?php echo $pd->product_id; ?>"
                    data-code="<?php echo $pd->code; ?>"
                    value="<?php echo $pd->sell_price; ?>" />
                </td>
                <td class="middle text-center">
                  <a href="javascript:void(0)" class="red" onclick="removePremiumItem('<?php echo $pd->product_id; ?>')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
                </td>
              </tr>
              <?php $no++; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <tr id="no-premium-row">
              <td colspan="7" class="text-center">No premium item</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">ใช้ซ้ำได้หรือไม่</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->canGroup == 1 ? 'btn-primary' : ''; ?>" id="btn-can-group-yes" onclick="toggleCanGroup(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->canGroup == 0 ? 'btn-primary' : ''; ?>" id="btn-can-group-no" onclick="toggleCanGroup(0)">No</button>
      </div>
    </div>
  </div>
</div><!-- form-horizontal -->

<div class="form-horizontal" id="condition-form">
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">จำนวนขั้นต่ำ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="min-qty" value="<?php echo $rule->minQty; ?>" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">มูลค่าขั้นต่ำ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="min-amount" value="<?php echo $rule->minAmount; ?>" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">ลำดับความสำคัญ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <select class="form-control input-sm" id="priority">
        <option value="1" <?php echo $rule->priority == 1 ? 'selected' : ''; ?>>1</option>
        <option value="2" <?php echo $rule->priority == 2 ? 'selected' : ''; ?>>2</option>
        <option value="3" <?php echo $rule->priority == 3 ? 'selected' : ''; ?>>3</option>
        <option value="4" <?php echo $rule->priority == 4 ? 'selected' : ''; ?>>4</option>
        <option value="5" <?php echo $rule->priority == 5 ? 'selected' : ''; ?>>5</option>
        <option value="6" <?php echo $rule->priority == 6 ? 'selected' : ''; ?>>6</option>
        <option value="7" <?php echo $rule->priority == 7 ? 'selected' : ''; ?>>7</option>
        <option value="8" <?php echo $rule->priority == 8 ? 'selected' : ''; ?>>8</option>
        <option value="9" <?php echo $rule->priority == 9 ? 'selected' : ''; ?>>9</option>
        <option value="10" <?php echo $rule->priority == 10 ? 'selected' : ''; ?>>10</option>
      </select>
    </div>
  </div>
</div><!-- form-horizontal -->

<div class="divider"></div>

<div class="form-horizontal" id="customer-form">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Customer</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">All Customer</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_customer ? 'btn-primary' : ''; ?>" id="btn-cust-all" onclick="toggleAllCustomer(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo !$rule->all_customer ? 'btn-primary' : ''; ?>" id="btn-cust-none" onclick="toggleAllCustomer(0)">No</button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer ID</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
      <input type="text" class="form-control input-sm" id="customer-id" placeholder="Specify the customer ID to include in this discount rule." <?php echo $c_active; ?> />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
      <input type="file" class="hide" name="customerFile" id="customer-file" accept=".xlsx" />
      <button type="button" class="btn btn-xs btn-success btn-white btn-block" id="btn-import-customer" onclick="getCustomerFile()" <?php echo $c_active; ?>><i class="fa fa-plus"></i> Import Excel</button>
    </div>
    <div class="col-lg-7-harf col-lg-offset-1-harf col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 <?php echo $rule->all_customer ? 'hide' : ''; ?>" id="customer-table" style="margin-top:10px; max-height:300px; overflow-y:auto;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:500px;">
        <thead>
          <tr>
            <th class="fix-width-40 text-center">
              <label>
                <input type="checkbox" class="ace" id="chk-all-customer" onchange="toggleAllCustomerCheck(this)" />
                <span class="lbl"></span>
              </label>
            </th>
            <th class="fix-width-30 text-center">No.</th>
            <th class="fix-width-100">Customer ID</th>
            <th class="min-width-250">Name</th>
            <th class="fix-width-30 text-center">
              <button type="button" class="btn btn-minier btn-danger" onclick="removeCustomerChecked()"><i class="fa fa-trash"></i></button>
            </th>
          </tr>
        </thead>
        <tbody id="customer-list">
          <?php if (!empty($customers)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($customers as $cs) : ?>
              <tr id="customer-row-<?php echo $cs->customer_id; ?>">
                <td class="middle text-center">
                  <label>
                    <input type="checkbox" class="ace chk-customer" data-id="<?php echo $cs->customer_id; ?>" data-code="<?php echo $cs->customer_code; ?>" data-name="<?php echo $cs->customer_name; ?>" />
                    <span class="lbl"></span>
                  </label>
                </td>
                <td class="middle text-center cust-no"><?php echo $no; ?></td>
                <td class="middle"><?php echo $cs->customer_code; ?></td>
                <td class="middle"><?php echo $cs->customer_name; ?></td>
                <td class="middle text-center">
                  <a href="javascript:void(0)" class="red" onclick="removeCustomer('<?php echo $cs->customer_id; ?>')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
                </td>
              </tr>
              <?php $no++; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <tr id="no-customer-row">
              <td colspan="5" class="text-center">No Customer</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Sales Team</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="sales-team" multiple="multiple" data-placeholder="Please select" <?php echo $c_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleSalesTeam($custRegions); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Group</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="customer-group" multiple="multiple" data-placeholder="Please select" <?php echo $c_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerGroups($custGroups); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Type</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="customer-type" multiple="multiple" data-placeholder="Please select" <?php echo $c_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerTypes($custTypes); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Area</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="customer-area" multiple="multiple" data-placeholder="Please select" <?php echo $c_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerArea($custAreas); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Grade</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="customer-grade" multiple="multiple" data-placeholder="Please select" <?php echo $c_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerGrade($custGrades); ?>
      </select>
    </div>
  </div>
</div><!-- form-horizontal -->

<div class="divider"></div>

<div class="form-horizontal" id="channel-form">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Channels</h4>
    </label>
  </div>

  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">All Channels</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_channels ? 'btn-primary' : ''; ?>" id="btn-channels-all" onclick="toggleAllChannels(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo !$rule->all_channels ? 'btn-primary' : ''; ?>" id="btn-channels-none" onclick="toggleAllChannels(0)">No</button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Channels</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="channels" multiple="multiple" data-placeholder="Please select" <?php echo $rule->all_channels ? 'disabled' : ''; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleChannels($channels); ?>
      </select>
    </div>
  </div>
</div><!-- channel-form -->

<div class="divider"></div>

<div class="form-horizontal" id="payment-form">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Payments</h4>
    </label>
  </div>

  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">All Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_payment ? 'btn-primary' : ''; ?>" id="btn-payments-all" onclick="toggleAllPayments(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo !$rule->all_payment ? 'btn-primary' : ''; ?>" id="btn-payments-none" onclick="toggleAllPayments(0)">No</button>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="payments" multiple="multiple" data-placeholder="Please select" <?php echo $rule->all_payment ? 'disabled' : ''; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultiplePaymentTerms($payments); ?>
      </select>
    </div>
  </div>
</div><!-- payment-form -->

<script>
  $('#sales-team').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true
  });

  $('#customer-group').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true
  });

  $('#customer-type').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true
  });

  $('#customer-area').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true
  });

  $('#customer-grade').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true
  });

  $('#channels').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true
  });

  $('#payments').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true
  });
</script>