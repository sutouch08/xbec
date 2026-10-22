<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-info" onclick="syncData()"><i class="fa fa-refresh"></i> Sync</button>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-10" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
			<label>Code</label>
			<input type="text" class="form-control input-sm search-box" name="code" value="<?php echo $code; ?>" />
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
			<label>Name</label>
			<input type="text" class="form-control input-sm search-box" name="name" value="<?php echo $name; ?>" />
		</div>

		<div class="col-xs-6 visible-xs">&nbsp;</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
			<label class="display-block not-show">buton</label>
			<button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
		</div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
			<label class="display-block not-show">buton</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>
	</div>
</form>
<hr class="margin-top-10 margin-bottom-10">
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-striped table-narrow border-1">
			<thead>
				<tr>
					<th class="fix-width-60 middle text-center">#</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="fix-width-200 middle">Name</th>
					<th class="fix-width-100 middle text-center">Dimensions</th>
					<th class="min-width-100"></th>
					<th class="fix-width-150 middle">Last sync</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php $no = $this->uri->segment($this->segment) + 1; ?>
					<?php foreach ($data as $rs) : ?>
						<tr>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle"><?php echo $rs->name; ?></td>
							<td class="middle text-center"><?php echo $rs->dimCode; ?></td>
							<td></td>
							<td class="middle"><?php echo (empty($rs->last_sync) ? "" : thai_date($rs->last_sync, TRUE)); ?></td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/cost_center.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>