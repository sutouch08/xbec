<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default" onclick="goBack('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> Back</button>
  </div>
</div>
<hr />
<div class="form-horizontal margin-top-30">
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Code</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <input type="text" class="form-control input-sm" id="code" value="<?php echo $code; ?>" disabled />
    </div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <input type="text" id="name" class="form-control input-sm r" maxlength="100" value="<?php echo $name; ?>" autocomplete="off" autofocus />
    </div>
  </div>

  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>

  <div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
      <button type="button" class="btn btn-white btn-success btn-100 btn-block-xs" onclick="update()"> Update</button>
    </div>
  </div>

  <input type="hidden" id="id" value="<?php echo $id; ?>" />
</div>

<script src="<?php echo base_url(); ?>scripts/masters/product_model.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>