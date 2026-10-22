<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="colo-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="colo-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<?php if ($this->pm->can_add) : ?>
			<button type="button" class="btn btn-white btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add new</button>
		<?php endif; ?>
	</div>
</div><!-- End Row -->
<hr class="padding-5" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-1-harf col-md-2 col-sm-2-harf col-xs-6 padding-5">
			<label>Code</label>
			<input type="text" class="form-control input-sm" name="policy_code" id="policy_code" value="<?php echo $code; ?>" />
		</div>

		<div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5">
			<label>Description</label>
			<input type="text" class="form-control input-sm" name="policy_name" id="policy_name" value="<?php echo $name; ?>" />
		</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>Status</label>
			<select class="form-control input-sm" name="active" id="active">
				<option value="all" <?php echo is_selected("all", $active); ?>>ทั้งหมด</option>
				<option value="1" <?php echo is_selected("1", $active); ?>>Active</option>
				<option value="0" <?php echo is_selected("0", $active); ?>>Inactive</option>
			</select>
		</div>
		
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>Start date</label>
			<input type="text" class="form-control input-sm width-100 text-center" name="start_date" id="start-date" value="<?php echo $start_date; ?>" />
		</div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>End date</label>
			<input type="text" class="form-control input-sm width-100 text-center" name="end_date" id="end-date" value="<?php echo $end_date; ?>" />
		</div>		

		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>
	</div>
	<input type="hidden" name="search" value="1" />
</form>
<hr class="margin-top-15 padding-5">
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-narrow border-1" style="min-width:1150px;">
			<thead>
				<tr>
					<th class="fix-width-90"></th>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="min-width-250 middle">Description</th>
					<th class="fix-width-100 middle">Start date</th>
					<th class="fix-width-100 middle">End date</th>
					<th class="fix-width-80 middle text-center">Status</th>
					<th class="fix-width-80 middle">Create at</th>
					<th class="fix-width-120 middle">Create by</th>
					<th class="fix-width-80 middle">Last update</th>
					<th class="fix-width-120 middle">Update by</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php $no = $this->uri->segment($this->segment) + 1; ?>
					<?php foreach ($data as $rs) : ?>
						<tr id="row-<?php echo $rs->id; ?>">
							<td>
								<button type="button" class="btn btn-minier btn-info" onclick="viewDetail(<?php echo $rs->id; ?>)">
									<i class="fa fa-eye"></i>
								</button>
								<?php if ($this->pm->can_edit) : ?>
									<button type="button" class="btn btn-minier btn-warning" onclick="edit(<?php echo $rs->id; ?>)">
										<i class="fa fa-pencil"></i>
									</button>
								<?php endif; ?>
								<?php if ($this->pm->can_delete) : ?>
									<button type="button" class="btn btn-minier btn-danger" onclick="confirmDelete('<?php echo $rs->id; ?>', '<?php echo addslashes($rs->code); ?>')">
										<i class="fa fa-trash"></i>
									</button>
								<?php endif; ?>
							</td>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle"><?php echo $rs->name; ?></td>
							<td class="middle"><?php echo thai_date($rs->start_date, FALSE, '.'); ?></td>
							<td class="middle"><?php echo thai_date($rs->end_date, FALSE, '.'); ?></td>
							<td class="middle text-center">
								<?php if($can_approve) : ?>
									<label>
										<input type="checkbox" class="ace ace-switch ace-switch-6" style="height:22px;" <?php echo is_checked(1, $rs->active); ?> onclick="setActive(<?php echo $rs->id; ?>, this)" />
										<span class="lbl"></span>
									</label>
								<?php else : ?>
									<?php echo is_active($rs->active); ?>
								<?php endif; ?>
							</td>
							<td class="middle"><?php echo thai_date($rs->date_add); ?></td>
							<td class="middle"><?php echo $rs->user; ?></td>
							<td class="middle"><?php echo is_null($rs->date_upd) ? '' : thai_date($rs->date_upd); ?></td>
							<td class="middle"><?php echo $rs->update_user; ?></td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="11" class="text-center">--- No content ---</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/discount/policy/policy.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/policy/policy_add.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>