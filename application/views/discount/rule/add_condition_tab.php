<div class="form-horizontal" id="condition-discount">
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
      <input type="number" class="form-control input-sm text-center disc-input" data-step="1" id="disc-1" value="0.00" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 2</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="2" id="disc-2" value="0.00" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 3</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="3" id="disc-3" value="0.00" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 4</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="4" id="disc-4" value="0.00" />
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <label>Step 5</label>
      <input type="number" class="form-control input-sm text-center disc-input" data-step="5" id="disc-5" value="0.00" />
    </div>
  </div>
</div><!-- form-horizontal -->


<div class="form-horizontal hide" id="condition-premium">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Premiums</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Premium</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="premium-qty" value="0" />
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> ชิ้น จากรายการต่อไปนี้ </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Premium SKU</label>
    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-8">
      <input type="text" class="form-control input-sm" id="premium-sku" placeholder="Specify the product code to add premium items." />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
      <input type="file" class="hide" name="premiumFile" id="premium-file" accept=".xlsx" />
      <button type="button" class="btn btn-xs btn-success btn-white btn-block" id="btn-import-premium" onclick="getPremiumFile()"><i class="fa fa-plus"></i> Import Excel</button>
    </div>
    <div class="col-lg-8-harf col-lg-offset-1-harf col-md-9-harf col-md-offset-2 col-sm-10 col-sm-offset-2 col-xs-12" style="max-height:300px; overflow-y:auto; margin-top:10px;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:560px; margin-bottom:0px;">
        <thead>
          <tr>
            <th class="fix-width-40 middle text-center">
              <label>
                <input type="checkbox" class="ace" id="chk-all-premium" onchange="toggleAllPremium(this)" />
                <span class="lbl"></span>
              </label>
            </th>
            <th class="fix-width-30 middle text-center">#</th>
            <th class="fix-width-100 middle">Item Code</th>
            <th class="min-width-200 middle">Description</th>
            <th class="fix-width-80 middle">Std Price</th>
            <th class="fix-width-80 middle">Net Price</th>
            <th class="fix-width-30 middle text-right">
              <button type="button" class="btn btn-minier btn-danger" onclick="removePremiumChecked()"><i class="fa fa-trash"></i></button>
            </th>
          </tr>
        </thead>
        <tbody id="premium-table">
          <tr id="no-premium-row">
            <td colspan="7" class="text-center">No premium item</td>
          </tr>
        </tbody>
      </table>
    </div>
    
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">ใช้ซ้ำได้หรือไม่</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-70" id="btn-can-group-yes" onclick="toggleCanGroup(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70 btn-primary" id="btn-can-group-no" onclick="toggleCanGroup(0)">No</button>
      </div>
    </div>
  </div>
</div><!-- form-horizontal -->


<div class="form-horizontal" id="condition-form">
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">จำนวนขั้นต่ำ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="min-qty" value="0" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">มูลค่าขั้นต่ำ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <input type="number" class="form-control input-sm text-center" id="min-amount" value="0.00" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">ลำดับความสำคัญ</label>
    <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-2">
      <select class="form-control input-sm" id="priority">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
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
        <button type="button" class="btn btn-sm btn-70" id="btn-cust-all" onclick="toggleAllCustomer(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70 btn-primary" id="btn-cust-none" onclick="toggleAllCustomer(0)">No</button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer ID</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
      <input type="text" class="form-control input-sm" id="customer-id" placeholder="Specify the customer ID to include in this discount rule." />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
      <input type="file" class="hide" name="customerFile" id="customer-file" accept=".xlsx" />
      <button type="button" class="btn btn-xs btn-success btn-white btn-block" id="btn-import-customer" onclick="getCustomerFile()"><i class="fa fa-plus"></i> Import Excel</button>
    </div>
    <div class="col-lg-6 col-lg-offset-1-harf col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12" id="customer-table" style="margin-top:10px; max-height:300px; overflow-y:auto;">
      <table class="table table-striped table-hover table-narrow border-1" style="margin-bottom:0px;">
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
          <tr id="no-customer-row">
            <td colspan="5" class="text-center">No Customer</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Sales Team</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="sales-team" multiple="multiple" data-placeholder="Please select">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleSalesTeam(); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Group</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="customer-group" multiple="multiple" data-placeholder="Please select">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerGroups(); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Type</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="customer-type" multiple="multiple" data-placeholder="Please select">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerTypes(); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Area</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="customer-area" multiple="multiple" data-placeholder="Please select">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerArea(); ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Customer Grade</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="customer-grade" multiple="multiple" data-placeholder="Please select">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCustomerGrade(); ?>
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
        <button type="button" class="btn btn-sm btn-70 btn-primary" id="btn-channels-all" onclick="toggleAllChannels(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70" id="btn-channels-none" onclick="toggleAllChannels(0)">No</button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Channels</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="channels" multiple="multiple" data-placeholder="Please select" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleChannels(); ?>
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
        <button type="button" class="btn btn-sm btn-70 btn-primary" id="btn-payments-all" onclick="toggleAllPayments(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70" id="btn-payments-none" onclick="toggleAllPayments(0)">No</button>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="payments" multiple="multiple" data-placeholder="Please select" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultiplePaymentTerms(); ?>
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