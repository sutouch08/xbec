<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-default" onclick="goBack('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> Back</button>
		<button type="button" class="btn btn-white btn-primary" onclick="syncItem('<?php echo $code; ?>')"><i class="fa fa-refresh"></i> Sync</button>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-15 padding-5" />
<form class="form-horizontal" method="post">
	<div class="row" style="margin-left:0px; margin-right:0px;">
		<!-- left column -->
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Code</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<input type="text" class="form-control" value="<?php echo $code; ?>" disabled />
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Name</label>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" value="<?php echo $name; ?>" disabled />
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Cost</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<input type="number" step="any" name="cost" id="cost" class="form-control" value="<?php echo round($cost, 2); ?>" disabled />
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Price</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<input type="number" step="any" name="price" id="price" class="form-control" value="<?php echo round($price, 2); ?>" disabled />
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Units of measure</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select class="form-control" name="uom" id="uom" disabled>
						<option value="">โปรดเลือก</option>
						<?php echo select_uom($uom_id); ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Vat group</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select class="form-control" name="vat_group" id="vat_group" disabled>
						<option value="">โปรดเลือก</option>
						<?php echo select_vat_group($vat_group); ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Model</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<input type="text" name="model" id="model" class="form-control" value="<?php echo $model; ?>" disabled />
					<input type="hidden" name="model_id" id="model_id" value="<?php echo $model_id; ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Brand</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select name="brand" id="brand" class="form-control" disabled>
						<option value="">โปรดเลือก</option>
						<?php echo select_product_brand($brand_id); ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Category</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select name="category" id="category" class="form-control" disabled>
						<option value="">โปรดเลือก</option>
						<?php echo select_product_category($category_id); ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Type</label>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<select name="type" id="type" class="form-control" disabled>
						<option value="">โปรดเลือก</option>
						<?php echo select_product_type($type_id); ?>
					</select>
				</div>
			</div>


			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 control-label no-padding-right hidden-xs">Status</label>
				<label class="col-xs-2 control-label text-right visible-xs" style="padding-top:5px;">Status</label>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
					<label style="padding-top:8px; padding-left:15px;">
						<?php if ($status == 1) : ?>
							<span class="green">Active</span>
						<?php else : ?>
							<span class="red">Inactive</span>
						<?php endif; ?>
					</label>
				</div>
				<label class="col-lg-2 col-md-2 col-sm-2 control-label no-padding-right hidden-xs">Cover : </label>
				<label for="is_cover" class="col-xs-2 control-label text-right visible-xs" style="padding-top:5px;">Cover : </label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<label style="padding-top:8px; padding-left:15px;">
						<?php if ($is_cover == 1) : ?>
							<span class="green">Yes</span>
						<?php else : ?>
							<span class="grey">No</span>
						<?php endif; ?>
					</label>
				</div>
			</div>
			<div class="divider-hidden"></div>

			<div class="divider-hidden"></div>

		</div>

		<!-- right column -->
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center">
				<span class="profile-picture">
					<img class="editable img-responsive" src="<?php echo get_product_image($id, 'medium'); ?>">
				</span>
			</div>
			<div class="divider-hidden"></div>
		</div> <!-- end right column-->
	</div><!--/ row  -->
</form>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="blue">Change Image</h4>
			</div>
			<form class="no-margin" id="imageForm">
				<div class="modal-body">
					<div style="width:75%;margin-left:12%;">
						<label id="btn-select-file" class="ace-file-input ace-file-multiple">
							<input type="file" name="image" id="image" accept="image/*" style="display:none;" />
							<span class="ace-file-container" data-title="Click to choose new Image">
								<span class="ace-file-name" data-title="No File ...">
									<i class=" ace-icon ace-icon fa fa-picture-o"></i>
								</span>
							</span>
						</label>
						<div id="block-image" style="opacity:0;">
							<div id="previewImg" class="width-100 center"></div>
							<span onClick="removeFile()" style="position:absolute; left:385px; top:1px; cursor:pointer; color:red;">
								<i class="fa fa-times fa-2x"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="modal-footer center">
					<button type="button" class="btn btn-sm btn-success" onclick="doUpload()"><i class="ace-icon fa fa-check"></i> Submit</button>
					<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/products.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/sync_product.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>