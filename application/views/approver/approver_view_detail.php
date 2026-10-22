<?php $this->load->view('include/header'); ?>
<style>
	.fix-header {
		background-color: #f8f8f8;
		outline: none;
		border-bottom: 1px solid #ddd;
	}
</style>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 padding-top-10">
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
			<input type="text" class="form-control input-sm" value="<?php echo display_name($approver->user_id); ?>" readonly />
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Status</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<label class="fix-width-100" style="margin-top:7px; padding-left: 10px;">
				<?php echo is_active($approver->status); ?>
				<span class="lbl">&nbsp;&nbsp;<?php echo ($approver->status == 1 ? 'Active' : 'Inactive'); ?></span>
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Order Approval</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<label class="fix-width-100" style="margin-top:7px; padding-left: 10px;">
				<?php echo is_active($approver->ap_order); ?>
				<span class="lbl">&nbsp;&nbsp;<?php echo ($approver->ap_order == 1 ? 'Yes' : 'No'); ?></span>
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Promotion Approval</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<label class="fix-width-100" style="margin-top:7px; padding-left: 10px;">
				<?php echo is_active($approver->ap_promotion); ?>
				<span class="lbl">&nbsp;&nbsp;<?php echo ($approver->ap_promotion == 1 ? 'Yes' : 'No'); ?></span>
			</label>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Visible GP</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<label class="fix-width-100" style="margin-top:7px; padding-left: 10px;">
				<?php echo is_active($approver->visible_gp); ?>
				<span class="lbl">&nbsp;&nbsp;<?php echo ($approver->visible_gp == 1 ? 'Yes' : 'No'); ?></span>
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
						<th class="fix-width-40 text-center">#</th>
						<th class="fix-width-80">Code</th>
						<th class="min-width-100">Name</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($sales_team)) : ?>
						<?php foreach ($sales_team as $rs) : ?>
						<?php if(in_array($rs->id, $ap_team)) : ?>
							<tr>
								<td class="text-center"><i class="fa fa-check green"></i></td>
								<td><?php echo $rs->code; ?></td>
								<td><?php echo $rs->name; ?></td>
							</tr>
						<?php endif; ?>
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
						<th class="fix-width-40 text-center fix-header">#</th>
						<th class="fix-width-80 fix-header">Code</th>
						<th class="min-width-100 fix-header">Name</th>
						<th class="fix-width-100 fix-header">Max Disc(%)</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($brand)) : ?>					
						<?php foreach ($brand as $rs) : ?>
							<?php if(isset($ap_brand[$rs->id])) : ?>
								<tr>
									<td class="text-center"><i class="fa fa-check green"></i></td>									
									<td><?php echo $rs->code; ?></td>
									<td><?php echo $rs->name; ?></td>
									<td><?php echo number($ap_brand[$rs->id], 2); ?></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="divider-hidden"></div>
	<div class="divider-hidden"></div>
</div>
<script>
	$('#user').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/approver/approver.js?v=<?php echo date('YmdH'); ?>"></script>
<?php $this->load->view('include/footer'); ?>