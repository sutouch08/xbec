<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
  </div>
</div><!-- End Row -->
<hr class="margin-bottom-30" />
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-4 control-label no-padding-right">Code</label>
    <div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-8">
      <input type="text" class="form-control input-sm" value="<?php echo $code; ?>" disabled />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="code-error"></div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-4 control-label no-padding-right">Name</label>
    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-8">
      <input type="text" class="form-control input-sm" id="name" maxlength="100" value="<?php echo $name; ?>" autocomplete="off" autofocus />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-8 control-label no-padding-right">Order Reserve Limit</label>
    <div class="col-lg-1-harf col-md-2 col-sm-2-harf col-xs-4">
      <input type="text" class="form-control input-sm text-right" id="reserve-amount" value="<?php echo number($reserve_amount, 2); ?>" placeholder="Set the maximum reserve value per user for this team." />
    </div>
    <div class="help-block col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-9 col-sm-offset-3 col-xs-12">Set the maximum reserve value per user for this team.</div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-8 control-label no-padding-right">Order Reserve Age</label>
    <div class="col-lg-1-harf col-md-2 col-sm-2-harf col-xs-4">
      <div class="input-group">
        <input type="text" class="form-control input-sm text-right" id="reserve-age" value="<?php echo $reserve_age; ?>" placeholder="Set the maximum reserve age per user for this team." />
        <span class="input-group-addon">days</span>
      </div>
    </div>
    <div class="help-block col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-9 col-sm-offset-3 col-xs-12">Set the maximum reserve age per user for this team. When exceeded, the order will be automatically canceled.</div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-8 control-label no-padding-right">Order Draft Age</label>
    <div class="col-lg-1-harf col-md-2 col-sm-2-harf col-xs-4">
      <div class="input-group">
        <input type="text" class="form-control input-sm text-right" id="draft-age" value="<?php echo $draft_age; ?>" placeholder="Set the maximum draft age per user for this team." />
        <span class="input-group-addon">days</span>
      </div>
    </div>
    <div class="help-block col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-9 col-sm-offset-3 col-xs-12">Set the maximum draft age per user for this team. When exceeded, the order will be automatically canceled.</div>
  </div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>

  <div class="form-group">
    <div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-9 col-sm-offset-3 col-xs-12">
      <button type="button" class="btn btn-white btn-success btn-100 btn-block-xs" onclick="update()">Update</button>
    </div>
  </div>

  <input type="hidden" id="id" value="<?php echo $id; ?>">
</div>

<script src="<?php echo base_url(); ?>scripts/masters/sale_team.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>