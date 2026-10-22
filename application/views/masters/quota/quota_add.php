<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
  </div>
</div><!-- End Row -->
<hr class="padding-5" />
<div class="form-horizontal margin-top-30">
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Code</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <input type="text" name="code" id="code" class="width-100" maxlength="10" value="" onkeyup="validCode(this)" required autofocus />
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">&nbsp;</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <label style="margin-top:5px;">
        <input type="checkbox" name="listed" id="listed" class="ace" value="1" checked />
        <span class="lbl"> List in Sales Order</span>
      </label>
    </div>
  </div>

  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>

  <div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
      <button type="button" class="btn btn-white btn-success btn-100 btn-block-xs" onclick="saveAdd()"> Add</button>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/quota.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>