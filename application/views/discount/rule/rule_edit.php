<?php $this->load->view('include/header'); ?>
<?php $this->load->view('discount/rule/style'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 padding-top-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default" onclick="goBack('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> Back</button>
    <div class="btn-group">
      <button type="button" class="btn btn-white btn-purple dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-cloud-download"></i> &nbsp; Download templates <i class="ace-icon fa fa-angle-down icon-on-right"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="purple"><a href="#" onclick="getSkuTemplate()"><i class="fa fa-tags"></i>&nbsp; SKU Template</a></li>
        <li class="purple"><a href="#" onclick="getCustomerTemplate()"><i class="fa fa-users"></i>&nbsp; Customer Template</a></li>
      </ul>
    </div>
  </div>
</div>
<hr />
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Document No.</label>
    <input type="text" class="form-control input-sm text-center" id="code" value="<?php echo $rule->code; ?>" disabled />
  </div>
  <div class="col-lg-4 col-md-7-harf col-sm-7 col-xs-8 padding-5">
    <label>Description</label>
    <input type="text" class="form-control input-sm" maxlength="200" id="name" value="<?php echo $rule->name; ?>" autocomplete="off" />
  </div>
  <div class="col-lg-1-harf col-md-2-harf col-sm-3 col-xs-6 padding-5">
    <label>Discount method</label>
    <select class="form-control input-sm" id="condition-type" onchange="updateConditionLayout()">
      <option value="P" <?php echo is_selected('P', $rule->type); ?>>Percentage</option>
      <option value="N" <?php echo is_selected('N', $rule->type); ?>>Price Override</option>
      <option value="F" <?php echo is_selected('F', $rule->type); ?>>Premium</option>
    </select>
  </div>
  <div class="col-lg-3 col-md-3-harf col-sm-4 col-xs-6 padding-5">
    <label>Promotion No.</label>
    <select class="form-control input-sm" id="policy">
      <option value="">Please select</option>
      <?php echo select_promotion($rule->id_policy); ?>
    </select>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block" style="margin-left:4px;">Active</label>
    <label style="height: 22px; padding-top:4px;">
      <input class="ace ace-switch ace-switch-6" type="checkbox" id="active" value="1" <?php echo is_checked(1, $rule->active); ?> />
      <span class="lbl"></span>
    </label>
  </div>
  <?php if ($this->pm->can_edit) : ?>
    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label class="display-block not-show">buton</label>
      <button type="button" class="btn btn-white btn-success btn-block" onclick="update()"> Save</button>
    </div>
  <?php endif; ?>
</div><!-- row -->
<hr />

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <div class="tabable">
      <ul class="nav nav-tabs" id="discount-rule-tabs">
        <li class="active"><a data-toggle="tab" href="#products-tab" aria-expanded="true">Product</a></li>
        <li class=""><a data-toggle="tab" href="#conditions-tab" aria-expanded="false">Conditions</a></li>
      </ul>

      <div class="tab-content">
        <div id="products-tab" class="tab-pane fade active in">
          <?php $this->load->view('discount/rule/edit_product_tab'); ?>
        </div>

        <div id="conditions-tab" class="tab-pane fade">
          <?php $this->load->view('discount/rule/edit_condition_tab'); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="rule_id" value="<?php echo $rule->id; ?>" />
<input type="hidden" id="all-product" value="<?php echo $rule->all_product; ?>" />
<input type="hidden" id="all-customer" value="<?php echo $rule->all_customer; ?>" />
<input type="hidden" id="all-channels" value="<?php echo $rule->all_channels; ?>" />
<input type="hidden" id="all-payments" value="<?php echo $rule->all_payment; ?>" />
<input type="hidden" id="can-group" value="<?php echo $rule->canGroup; ?>" />
<input type="hidden" id="valid-discount" value="1" />
<input type="hidden" id="valid-min-qty" value="1" />
<input type="hidden" id="valid-min-amount" value="1" />

<?php $this->load->view('discount/rule/template'); ?>
<?php $this->load->view('discount/rule/import_excel'); ?>

<script>
  $('#policy').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/discount/rule/rule.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/rule/rule_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/rule/products_tab.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/rule/conditions_tab.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>