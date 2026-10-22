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
      <input type="number" class="form-control input-sm text-center disc-input" data-step="1" id="disc-1" value="<?php echo $rule->disc1; ?>" disabled />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 2</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="2" id="disc-2" value="<?php echo $rule->disc2; ?>" disabled />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 3</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="3" id="disc-3" value="<?php echo $rule->disc3; ?>" disabled />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 4</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="4" id="disc-4" value="<?php echo $rule->disc4; ?>" disabled />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 5</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="5" id="disc-5" value="<?php echo $rule->disc5; ?>" disabled />
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
      <input type="number" class="form-control input-sm text-center" id="premium-qty" value="<?php echo $rule->freeQty; ?>" disabled />
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> ชิ้น จากรายการต่อไปนี้ </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Premium SKU</label>    
    <div class="col-lg-8 col-md-10 col-sm-10 col-xs-12" style="max-height:300px; overflow-y:auto; margin-top:10px;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:590px; margin-bottom:0px;">
        <thead>
          <tr>            
            <th class="fix-width-40 middle text-center">#</th>
            <th class="fix-width-100 middle">Item Code</th>
            <th class="min-width-250 middle">Description</th>
            <th class="fix-width-100 middle">Std Price</th>
            <th class="fix-width-100 middle">Premium Price</th>            
          </tr>
        </thead>
        <tbody id="premium-table">
          <?php if (!empty($premiums)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($premiums as $pd) : ?>
              <tr>                
                <td class="middle text-center premium-no"><?php echo $no; ?></td>
                <td class="middle"><?php echo $pd->code; ?></td>
                <td class="middle"><?php echo $pd->name; ?></td>
                <td class="middle text-right"><?php echo number($pd->price, 2); ?></td>
                <td class="middle text-right"><?php echo number($pd->sell_price, 2); ?></td>                
              </tr>
              <?php $no++; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <tr id="no-premium-row">
              <td colspan="5" class="text-center">No premium item</td>
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
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->canGroup == 1 ? 'btn-primary' : ''; ?>" disabled>Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->canGroup == 0 ? 'btn-primary' : ''; ?>" disabled>No</button>
      </div>
    </div>
  </div>
</div><!-- form-horizontal -->

<div class="form-horizontal" id="condition-form">
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">จำนวนขั้นต่ำ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="min-qty" value="<?php echo $rule->minQty; ?>" disabled/>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">มูลค่าขั้นต่ำ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="min-amount" value="<?php echo $rule->minAmount; ?>" disabled/>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">ลำดับความสำคัญ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="priority" value="<?php echo $rule->priority; ?>" disabled/>
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
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_customer ? 'btn-primary' : ''; ?>" disabled>Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo !$rule->all_customer ? 'btn-primary' : ''; ?>" disabled>No</button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer ID</label>    
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12" id="customer-table" style="margin-top:10px; max-height:300px; overflow-y:auto;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:400px; margin-bottom:0px;">
        <thead>
          <tr>            
            <th class="fix-width-40 text-center">#</th>
            <th class="fix-width-100">Customer ID</th>
            <th class="min-width-250">Name</th>            
          </tr>
        </thead>
        <tbody id="customer-list">
          <?php if (!empty($customers)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($customers as $cs) : ?>
              <tr>                
                <td class="middle text-center cust-no"><?php echo $no; ?></td>
                <td class="middle"><?php echo $cs->customer_code; ?></td>
                <td class="middle"><?php echo $cs->customer_name; ?></td>                
              </tr>
              <?php $no++; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <tr id="no-customer-row">
              <td colspan="3" class="text-center">No Customer</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Sales Team</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="sales-team" multiple="multiple" data-placeholder="No sales team selected" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleSalesTeam($custRegions); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Group</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="customer-group" multiple="multiple" data-placeholder="No customer group selected" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerGroups($custGroups); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Type</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="customer-type" multiple="multiple" data-placeholder="No customer type selected" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerTypes($custTypes); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Area</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="customer-area" multiple="multiple" data-placeholder="No customer area selected" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerArea($custAreas); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Grade</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="customer-grade" multiple="multiple" data-placeholder="No customer grade selected" disabled>
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
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_channels ? 'btn-primary' : ''; ?>" disabled>Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo !$rule->all_channels ? 'btn-primary' : ''; ?>" disabled>No</button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Channels</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="channels" multiple="multiple" data-placeholder="No channels selected" disabled>
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
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_payment ? 'btn-primary' : ''; ?>" disabled>Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo !$rule->all_payment ? 'btn-primary' : ''; ?>" disabled>No</button>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Payment</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="payments" multiple="multiple" data-placeholder="No payments selected" disabled>
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