<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-xs btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    </p>
  </div>
</div><!-- End Row -->
<hr />
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
    <label>Document No</label>
    <input type="text" class="form-control input-sm" id="code" value="" disabled />
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 padding-5">
    <label>Description</label>
    <input type="text" class="form-control input-sm r" maxlength="100" id="name" value="" autocomplete="off" autofocus/>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Start date</label>
    <input type="text" class="form-control input-sm text-center r" id="start-date" value="<?php echo date('d-m-Y'); ?>" autocomplete="off" />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>End date</label>
    <input type="text" class="form-control input-sm text-center r" id="end-date" value="<?php echo date('d-m-Y'); ?>" autocomplete="off" />
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-white btn-success btn-block" style="height:31px; padding:4px;" onclick="add()">Add</button>
  </div>

</div>
<hr class="margin-top-15">

<script src="<?php echo base_url(); ?>scripts/discount/policy/policy.js"></script>
<script src="<?php echo base_url(); ?>scripts/discount/policy/policy_add.js"></script>

<?php $this->load->view('include/footer'); ?>