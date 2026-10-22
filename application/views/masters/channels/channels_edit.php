<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"> <?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default" onclick="goBack('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> &nbsp;Back</button>
  </div>
</div><!-- End Row -->
<hr />
<div class="form-horizontal margin-top-30">
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Channels Code</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <input type="text" class="form-control input-sm r" maxlength="50" value="<?php echo $code; ?>" disabled />
    </div>
    <div class="col-xs-12 col-sm-reset inline red margin-top-5" id="code-error"></div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Channels Name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <input type="text" id="name" class="form-control input-sm r" maxlength="50" value="<?php echo $name; ?>" autocomplete="off" />
    </div>
    <div class="col-xs-12 col-sm-reset inline red margin-top-5" id="name-error"></div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Position</label>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
      <input type="number" id="position" class="form-control input-mini text-center r" value="<?php echo $position; ?>" />
    </div>
  </div>

  <div class="divider-hidden"></div>
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <label>
        <input type="checkbox" class="ace" id="active" <?php echo is_checked('1', $active); ?> />
        <span class="lbl">&nbsp; &nbsp;Active</span>
      </label>
    </div>
  </div>

  <div class="divider-hidden"></div>
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <label>
        <input type="checkbox" class="ace" id="is_default" <?php echo is_checked('1', $is_default); ?> />
        <span class="lbl">&nbsp; &nbsp;Default</span>
      </label>
    </div>
  </div>

  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>

  <div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
      <button type="button" class="btn btn-white btn-success btn-100 btn-block-xs" onclick="update()">Update</button>
    </div>
  </div>

  <input type="hidden" id="id" value="<?php echo $id; ?>">
</div>

<script src="<?php echo base_url(); ?>scripts/masters/channels.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>