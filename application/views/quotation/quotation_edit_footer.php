<div class="row">
  <!--- left column -->
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <div class="form-horizontal">

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 control-label no-padding-right">Sales Employee</label>
        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
          <select class="form-control input-sm r" id="sale_id">
            <?php $sale_id = empty($order->SlpCode) ? $this->_user->sale_id : $order->SlpCode; ?>
            <?php echo select_saleman($sale_id); ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 col-xs-12 control-label no-padding-right">Owner</label>
        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
          <?php $active = 1; ?>
          <select class="form-control input-sm r" id="owner">            
            <option value="">-No Owner-</option>
            <?php $owner = empty($order->OwnerCode) ? $this->_user->emp_id : $order->OwnerCode; ?>
            <?php echo select_employee($owner, $active); ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 col-xs-12 control-label no-padding-right">Remark</label>
        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
          <textarea id="comments" maxlength="254" class="form-control" style="height:100px;"><?php echo $order->Comments; ?></textarea>
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
          <input type="hidden" id="totalAmount" value="<?php echo round($totalAmount, 2); ?>">
          <input type="text" class="form-control input-sm text-right" id="totalAmountLabel" value="<?php echo number($totalAmount, 2); ?>" disabled>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-6 col-md-4 col-sm-4 col-xs-3 control-label no-padding-right">Discount</label>
        <div class="col-lg-2 col-md-4 col-sm-3 col-xs-3 padding-5">
          <span class="input-icon input-icon-right">
            <input type="number" id="discPrcnt" class="form-control input-sm" value="<?php echo $order->DiscPrcnt; ?>" />
            <i class="ace-icon fa fa-percent"></i>
          </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="discAmount" value="<?php echo $order->DiscAmount; ?>" />
          <input type="text" id="discAmountLabel" class="form-control input-sm text-right" value="<?php echo number($order->DiscAmount, 2); ?>" disabled>
        </div>
      </div>

      <div class="form-group hide">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Rouding</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="number" id="roundDif" class="form-control input-sm text-right" value="<?php echo $order->RoundDif; ?>" />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Tax</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="tax" value="<?php echo round($order->VatSum, 2); ?>" />
          <input type="text" id="taxLabel" class="form-control input-sm text-right" value="<?php echo number($order->VatSum, 2); ?>" disabled />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="docTotal" value="<?php echo round($order->DocTotal, 2); ?>" />
          <input type="text" id="docTotalLabel" class="form-control input-sm text-right" value="<?php echo number($order->DocTotal, 2); ?>" disabled />
        </div>
      </div>
    </div>
  </div>

  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>

  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-primary btn-100 btn-save" id="btn-save" onclick="update(0)">Save</button>
    <button type="button" class="btn btn-white btn-warning btn-100 btn-save" onclick="leave('<?php echo $backUrl; ?>')">Cancel</button>
    <button type="button" class="btn btn-white btn-info btn-100 btn-save" id="btn-draft" onclick="update(1)">Save AS Draft</button>
  </div>  
</div>

<script>
  $('#owner').select2();
  $('#sale_id').select2();
</script>