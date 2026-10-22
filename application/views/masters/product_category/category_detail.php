<?php $this->load->view('include/header'); ?>
<style>
	input[type=radio].ace:checked + .lbl::before {
		color:#32a3ce;
	}
</style>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
			<button type="button" class="btn btn-white btn-default" onclick="goBack('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> Back</button>
			<button type="button" class="btn btn-white btn-primary" onclick="create_sap()"><i class="fa fa-send"></i> Create On SAP</button>
			<button type="button" class="btn btn-white btn-success" onclick="update_sap()"><i class="fa fa-send"></i> Update On SAP</button>
	</div>
</div><!-- End Row -->
<hr class="padding-5"/>

<form class="form-horizontal" id="addForm" method="post">
	<div class="row" style="margin-left:0px; margin-right:0px;">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<div class="form-group margin-top-30">
		    <label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label no-padding-right">Code</label>
		    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-9">
						<label class="visible-xs">Code</label>
					<input type="text" name="name" id="name" class="width-100" value="<?php echo $code; ?>" disabled />
		    </div>
		    <div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
		  </div>

		  <div class="form-group ">
		    <label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label no-padding-right">Name</label>
		    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-9">
						<label class="visible-xs">Name</label>
					<input type="text" name="name" id="name" class="width-100" value="<?php echo $name; ?>" disabled />
		    </div>
				<div class="col-xs-3 visible-xs">
					<label>Level</label>
					<input type="text" class="width-100 text-center" value="<?php echo $level; ?>" disabled />
		    </div>
		    <div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
		  </div>

			<div class="form-group hidden-xs">
		    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Level</label>
		    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
					<input type="text" class="width-100 text-center" value="<?php echo $level; ?>" disabled />
		    </div>
		  </div>



			<div class="form-group">
		    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Parent</label>
		    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" style="padding-top:8px;">
					<?php echo getCategoryTree($id, "view"); ?>
		    </div>
		  </div>

			<div class="divider-hidden"></div>

			<input type="hidden" id="id" value="<?php echo $id; ?>" />
		</div>

		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="col-sm-12 col-xs-12 center">
				<span class="profile-picture">
					<img class="editable img-responsive" src="<?php echo get_category_path($code); ?>" style="max-width:300px;">
				</span>
			</div>
			<div class="divider-hidden"></div>
		</div> <!-- end right column-->
	</div>

</form>

<script src="<?php echo base_url(); ?>scripts/masters/product_category.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
