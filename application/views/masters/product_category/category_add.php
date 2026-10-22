<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
  </div>
</div><!-- End Row -->
<hr />
<div class="form-horizontal">
  <div class="form-group margin-top-30">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Code</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <input type="text" id="code" class="form-control input-sm" value="" autofocus autocomplete="off" />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="code-error"></div>
  </div>
  <div class="form-group margin-top-30">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <input type="text" name="name" id="name" class="form-control input-sm" value="" autocomplete="off" />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
  </div>

  <div class="divider-hidden"></div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Parent</label>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" style="padding-top:8px;">
      <?php echo getCategoryTree(); ?>
    </div>
  </div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>

  <div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
      <button type="button" class="btn btn-white btn-success btn-100 btn-block-xs" onclick="save()">Add</button>
    </div>
  </div>  
</div>

<script src="<?php echo base_url(); ?>scripts/masters/product_category.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>