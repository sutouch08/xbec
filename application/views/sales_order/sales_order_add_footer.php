<div class="row">
  <!--- left column -->
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <div class="form-horizontal">

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 control-label no-padding-right">Sales Employee</label>
        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
          <select class="form-control input-sm" id="sale_id">
            <option value=""></option>
            <?php echo select_saleman($this->_user->sale_id); ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 col-xs-12 control-label no-padding-right">Owner</label>
        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
          <select class="form-control input-sm" id="owner">
            <option value=""></option>
            <?php $active = 1; ?>
            <?php echo select_employee($this->_user->emp_id, $active); ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 col-xs-12 control-label no-padding-right">Remark</label>
        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
          <textarea id="comments" maxlength="254" class="form-control" style="height:100px;"></textarea>
        </div>
      </div>

    </div>
  </div>


  <!--- right column -->
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <div class="form-horizontal">
      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total Before Discount</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="totalAmount" value="0.00">
          <input type="text" class="form-control input-sm text-right" id="totalAmountLabel" value="0.00" disabled>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-6 col-md-4 col-sm-4 col-xs-3 control-label no-padding-right">Discount</label>
        <div class="col-lg-2 col-md-4 col-sm-3 col-xs-3 padding-5">
          <span class="input-icon input-icon-right">
            <input type="number" id="discPrcnt" class="form-control input-sm" value="0.00" />
            <i class="ace-icon fa fa-percent"></i>
          </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="discAmount" value="0.00" />
          <input type="text" id="discAmountLabel" class="form-control input-sm text-right" value="0.00" disabled>
        </div>
      </div>


      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Tax</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="tax" value="0.00" />
          <input type="text" id="taxLabel" class="form-control input-sm text-right" value="0.00" disabled />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="docTotal" value="0.00" />
          <input type="text" id="docTotalLabel" class="form-control input-sm text-right" value="0.00" disabled />
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Credit Balance <span class="font-size-11">(include this order)</span></label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="text" id="creditBalanceLabel" class="form-control input-sm text-right" value="0.00" disabled />
        </div>
      </div>
    </div>
  </div>

  <input type="hidden" id="total-cost" value="0.00" />
  <input type="hidden" id="total-gp" value="0.00" />

  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>  

  <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-sm btn-info btn-100" id="btn-check-free" onclick="getFreeItemRule()">ตรวจสอบของแถม</button>
    <button type="button" class="btn btn-sm btn-primary btn-100 hide" id="btn-save" onclick="validateFreeItem('add')">Save</button>
    <button type="button" class="btn btn-sm btn-warning btn-100" onclick="leave()">Cancel</button>
    <button type="button" class="btn btn-sm btn-info btn-100 hide" id="btn-draft" onclick="saveAsDraft('add')">Save AS Draft</button>
  </div> -->
</div>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <p class="margin-top-15" id="promotions-applied">Promotions applied : - </p>
  </div>

  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default btn-100" onclick="leave()">Cancel</button>
    <button type="button" class="btn btn-white btn-info btn-100" id="btn-check-free" onclick="getFreeItemRule()">ตรวจสอบของ Premium</button>
    <div class="btn-group dropup">
      <button type="button" id="btn-save" class="btn btn-white btn-primary btn-100 dropdown-toggle" data-toggle="dropdown" disabled>Save</button>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="primary"><a href="javascript:saveAsDraft('add')">Save as Draft</a></li>
        <?php if (getConfig('ALLOW_RESERVE') == 1) : ?>
          <li class="purple"><a href="javascript:saveAsReserve('add')">Save AS Reserv</a></li>
        <?php endif; ?>
        <li class="success"><a href="javascript:save('add')">Save</a></li>
      </ul>
    </div>
  </div>
</div>

<script>
  $('#owner').select2();
  $('#sale_id').select2();
</script>