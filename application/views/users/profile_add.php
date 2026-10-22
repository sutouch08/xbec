<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-sm-6">
		<p class="pull-right">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-30"/>
<form class="form-horizontal">
	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Profile name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" id="name" class="form-control" maxlength="50" value="" autofocus />
    </div>
    <div class="col-xs-12 col-sm-reset red" id="name-error"></div>
  </div>

	<div class="divider-hidden">
		<input type="text" class="hide"/>
	</div>
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
      <button type="button" class="btn btn-sm btn-success btn-100"  onclick="saveAdd()">Add</button>
    </div>
  </div>
</form>

<script src="<?php echo base_url(); ?>scripts/users/permission.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
