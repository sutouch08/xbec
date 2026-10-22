<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-info" onclick="syncData()"><i class="fa fa-refresh"></i> Sync</button>
		<?php if ($this->_SuperAdmin) : ?>
			<button type="button" class="btn btn-white btn-info" onclick="forceSyncData()"><i class="fa fa-refresh"></i> Sync All</button>
		<?php endif; ?>
	</div>
</div><!-- End Row -->
<hr />
<form id="searchForm" method="post">
	<div class="row">
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
			<label>Code</label>
			<input type="text" class="form-control input-sm search-box" name="code" value="<?php echo $code; ?>" />
		</div>

		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
			<label>Name</label>
			<input type="text" class="form-control input-sm search-box" name="name" value="<?php echo $name; ?>" />
		</div>

		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
			<label>Group</label>
			<select class="form-control input-sm filter" name="group">
				<option value="all">ทั้งหมด</option>
				<?php echo select_customer_group($group); ?>
			</select>
		</div>


		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
			<label>Sales Team</label>
			<select class="form-control input-sm filter" name="saleTeam">
				<option value="all">ทั้งหมด</option>
				<option value="0" <?php echo is_selected('0', $saleTeam); ?>>-- No Sale Team --</option>
				<?php echo select_customer_sales_team($saleTeam); ?>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
			<label>Payment</label>
			<select class="form-control input-sm filter" name="term">
				<option value="all">ทั้งหมด</option>
				<?php echo select_payment_term($term); ?>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
			<label>Sales Emp.</label>
			<select class="form-control input-sm filter" name="slp" id="slp">
				<option value="all">ทั้งหมด</option>
				<?php echo select_sale($slp); ?>
			</select>
		</div>

		<div class="col-lg-1 col-md-1 col-sm-2 col-xs-6 padding-5">
			<label>Status</label>
			<select class="form-control input-sm filter" name="status">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected($status, '1'); ?>>Active</option>
				<option value="0" <?php echo is_selected($status, '0'); ?>>Disactive</option>
			</select>
		</div>

		<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
		</div>

		<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>

	</div>
	<input type="hidden" name="search" value="1" />
</form>
<hr class="margin-top-15">
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-narrow border-1">
			<thead>
				<tr>
					<th class="fix-width-60"></th>
					<th class="fix-width-50 middle text-center">#</th>
					<th class="fix-width-50 middle text-center">Status</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="min-width-250 middle">Name</th>
					<th class="fix-width-150 middle">Group</th>
					<th class="fix-width-150 middle">Sales Team</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php $pageNo = get_zero($this->uri->segment($this->segment)); ?>
					<?php $no = $this->uri->segment($this->segment) + 1; ?>
					<?php foreach ($data as $rs) : ?>
						<tr style="font-size:12px;">
							<td class="text-right">
								<button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->id; ?>', <?php echo $pageNo; ?>)"><i class="fa fa-eye"></i></button>
								<?php if ($this->pm->can_delete) : ?>
									<button type="button" class="btn btn-minier btn-danger" onclick="getDelete('<?php echo $rs->CardCode; ?>', '<?php echo $rs->CardName; ?>')"><i class="fa fa-trash"></i></button>
								<?php endif; ?>
							</td>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle text-center"><?php echo is_active($rs->Status); ?></td>
							<td class="middle"><?php echo $rs->CardCode; ?></td>
							<td class="middle"> <?php echo $rs->CardName; ?></td>
							<td class="middle"><?php echo $rs->group_name; ?></td>
							<td class="middle"><?php echo $rs->SaleTeamName; ?></td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$('#slp').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/masters/customers.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/sync_customer.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>