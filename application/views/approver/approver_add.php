<?php $this->load->view('include/header'); ?>
<style>
	.fix-header {
		background-color: #f8f8f8;
		outline: none;
		border-bottom: 1px solid #ddd;
	}
</style>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 padding-top10">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-default" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-30" />
<div class="form-horizontal">
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Username</label>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control input-sm r" id="user">
				<option value="">Please Select</option>
				<?php echo select_user_id(); ?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-reset inline red margin-top-5" id="user-error"></div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Status</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<label class="fix-width-100" style="margin-top:7px;">
				<input type="radio" class="ace" name="status" value="1" checked />
				<span class="lbl">&nbsp; Active</span>
			</label>
			<label class="fix-width-100" style="margin-top:7px;">
				<input type="radio" class="ace" name="status" value="0" />
				<span class="lbl">&nbsp; Inactive</span>
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Order Approval</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<label class="fix-width-100" style="margin-top:7px;">
				<input type="radio" class="ace" name="ap_order" value="1" checked />
				<span class="lbl">&nbsp; Yes</span>
			</label>
			<label class="fix-width-100" style="margin-top:7px;">
				<input type="radio" class="ace" name="ap_order" value="0" />
				<span class="lbl">&nbsp; No</span>
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Promotion Approval</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<label class="fix-width-100" style="margin-top:7px;">
				<input type="radio" class="ace" name="ap_promotion" value="1" />
				<span class="lbl">&nbsp; Yes</span>
			</label>
			<label class="fix-width-100" style="margin-top:7px;">
				<input type="radio" class="ace" name="ap_promotion" value="0" checked />
				<span class="lbl">&nbsp; No</span>
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Visible GP</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<label class="fix-width-100" style="margin-top:7px;">
				<input type="radio" class="ace" name="visible_gp" value="1" />
				<span class="lbl">&nbsp; Yes</span>
			</label>
			<label class="fix-width-100" style="margin-top:7px;">
				<input type="radio" class="ace" name="visible_gp" value="0" checked />
				<span class="lbl">&nbsp; No</span>
			</label>
		</div>
	</div>

	<div class="divider"></div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Sales Team</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 table-responsive">
			<table class="table table-striped table-narrow border-1" style="margin-bottom: 0px;">
				<thead>
					<tr>
						<th class="fix-width-40 text-center">
							<label>
								<input type="checkbox" class="ace" id="check-all-team" onchange="checkAllTeam()" />
								<span class="lbl"></span>
							</label>
						</th>
						<th class="fix-width-80">Code</th>
						<th class="min-width-100">Name</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($sales_team)) : ?>
						<?php foreach ($sales_team as $rs) : ?>
							<tr>
								<td class="text-center">
									<label>
										<input type="checkbox" class="ace chk-team" value="<?php echo $rs->id; ?>" />
										<span class="lbl"></span>
									</label>
								</td>
								<td><?php echo $rs->code; ?></td>
								<td><?php echo $rs->name; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="divider"></div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Brand</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 table-responsive" style="max-height: 300px; overflow-y: auto;">
			<table class="table table-striped table-narrow tableFixHead border-1" style="margin-bottom: 0px;">
				<thead>
					<tr>
						<th class="fix-width-40 text-center fix-header">
							<label>
								<input type="checkbox" class="ace" id="check-all-brand" onchange="checkAllBrand()" />
								<span class="lbl"></span>
							</label>
						</th>
						<th class="fix-width-80 fix-header">Code</th>
						<th class="min-width-100 fix-header">Name</th>
						<th class="fix-width-100 fix-header">Max Disc(%)</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($brand)) : ?>
						<?php foreach ($brand as $rs) : ?>
							<tr>
								<td class="text-center">
									<label>
										<input type="checkbox" class="ace chk-brand" id="brand-chk-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" data-id="<?php echo $rs->id; ?>" />
										<span class="lbl"></span>
									</label>
								</td>
								<td><?php echo $rs->code; ?></td>
								<td><?php echo $rs->name; ?></td>
								<td>
									<input type="number" class="form-control input-xs text-center disc r" id="brand-disc-<?php echo $rs->id; ?>" data-id="<?php echo $rs->id; ?>" value="0.00" />
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="col-sm-reset inline red margin-top-5" id="team-error"></div>
	</div>

	<div class="divider-hidden"></div>	
	<div class="divider-hidden"></div>

	<div class="form-group">		
		<div class="col-lg-3 col-lg-offset-3 col-md-3 col-md-offset-3 col-sm-3 col-sm-offset-3 col-xs-12">
			<button type="button" class="btn btn-white btn-success btn-100" onclick="add()">Add</button>
		</div>
	</div>
</div>

<script>
	$('#user').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/approver/approver.js?v=<?php echo date('YmdH'); ?>"></script>
<?php $this->load->view('include/footer'); ?>