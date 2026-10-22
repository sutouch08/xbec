<?php $this->load->view('include/header'); ?>
<script>
	var USE_STRONG_PWD = <?php echo getConfig('USE_STRONG_PWD'); ?>;
</script>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
</div><!-- End Row -->
<hr class="margin-bottom-30"/>

<form class="form-horizontal" id="resetForm" method="post">
	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Username</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" class="form-control" id="uname" value="<?php echo $data->uname; ?>" disabled />
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Display name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" class="form-control" value="<?php echo $data->name; ?>" disabled />
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">รหัสผ่านปัจจุบัน</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<span class="input-icon input-icon-right width-100">
        <input type="password" name="cu-pwd" id="cu-pwd" class="width-100" autofocus />
				<i class="ace-icon fa fa-key"></i>
			</span>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" style="padding-left:15px;" id="cu-pwd-error"></div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">รหัสผ่านใหม่</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<span class="input-icon input-icon-right width-100">
        <input type="password" name="pwd" id="pwd" class="width-100" />
				<i class="ace-icon fa fa-key"></i>
			</span>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" style="padding-left:15px;" id="pwd-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">ยืนยันรหัสผ่านใหม่</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<span class="input-icon input-icon-right width-100">
        <input type="password" name="cm-pwd" id="cm-pwd" class="width-100" required />
				<i class="ace-icon fa fa-key"></i>
			</span>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" style="padding-left:15px;" id="cm-pwd-error"></div>
  </div>

	<div class="divider-hidden"></div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <p class="pull-right">
        <button type="button" class="btn btn-sm btn-success btn-100" onclick="changePassword()">เปลี่ยนรหัสผ่าน</button>
      </p>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline">
      &nbsp;
    </div>
  </div>
	<input type="hidden" name="user_id" id="user_id" value="<?php echo $data->id; ?>" />
</form>

<script src="<?php echo base_url(); ?>scripts/users/user_pwd.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
