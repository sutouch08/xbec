<?php $this->load->view('include/header'); ?>
<?php $this->load->view('discount/rule/style'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 padding-top-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <?php if(! isset($_GET['nomenu'])) : ?>      
      <button type="button" class="btn btn-white btn-default" onclick="goBack('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> Back</button>    
    <?php endif; ?>
  </div>
</div>
<hr />
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
    <label>Document No.</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $rule->code; ?>" readonly />
  </div>
  <div class="col-lg-8 col-md-7-harf col-sm-6-harf col-xs-8 padding-5">
    <label>Description</label>
    <input type="text" class="form-control input-sm" maxlength="200" id="name" value="<?php echo $rule->name; ?>" readonly />
  </div>
  <div class="col-lg-1-harf col-md-2-harf col-sm-2 col-xs-6 padding-5">
    <label>Disc. method</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $rule->type == 'P' ? 'Percentage' : ($rule->type == 'N' ? 'Price Override' : 'Premium'); ?>" readonly />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Status</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $rule->active ? 'Active' : 'Inactive'; ?>" readonly />
  </div>    
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
    <label>Promotion No.</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo !empty($promotion) ? $promotion->code : ''; ?>" readonly />
  </div>
  <div class="col-lg-5-harf col-md-3 col-sm-3 col-xs-8 padding-5">
    <label>Promotion Desc.</label>
    <input type="text" class="form-control input-sm" value="<?php echo !empty($promotion) ? $promotion->name : ''; ?>" readonly />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Create at</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo thai_date($rule->date_add, FALSE); ?>" readonly />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-8 padding-5">
    <label>Create by</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $rule->user; ?>" readonly />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Last update</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo !empty($rule->date_upd) && !empty($rule->update_user) ? thai_date($rule->date_upd, FALSE) : ''; ?>" readonly />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-8 padding-5">
    <label>Update by</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $rule->update_user; ?>" readonly />
  </div>
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
          <?php $this->load->view('discount/rule/view_product_tab'); ?>
        </div>

        <div id="conditions-tab" class="tab-pane fade">
          <?php $this->load->view('discount/rule/view_condition_tab'); ?>
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

<script src="<?php echo base_url(); ?>scripts/discount/rule/rule.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/rule/rule_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/rule/products_tab.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/rule/conditions_tab.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>