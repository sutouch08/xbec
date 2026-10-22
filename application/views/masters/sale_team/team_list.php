<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<?php if ($this->pm->can_add) : ?>
			<button type="button" class="btn btn-white btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add new</button>
		<?php endif; ?>
	</div>
</div><!-- End Row -->
<hr class="padding-5" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<label>Code</label>
			<input type="text" class="width-100 search-box" name="code" value="<?php echo $code; ?>" />
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
			<label>Name</label>
			<input type="text" class="width-100 search-box" name="name" value="<?php echo $name; ?>" />
		</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
			<label class="display-block not-show">buton</label>
			<button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
		</div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
			<label class="display-block not-show">buton</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>
	</div>
	<input type="hidden" name="search" value="1" />
</form>
<hr class="margin-top-15 padding-5">
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-narrow border-1" style="min-width: 1020px; margin-bottom: 0px;">
			<thead>
				<tr>
					<th class="fix-width-60"></th>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="min-width-200 middle">Name</th>
					<th class="fix-width-100 middle text-right">Draft Age</th>
					<th class="fix-width-100 middle text-right">Reserve Age</th>
					<th class="fix-width-100 middle text-right">Reserv Limit</th>
					<th class="fix-width-80 middle text-center">Members</th>
					<th class="fix-width-120 middle">Last Modified</th>
					<th class="fix-width-120 middle">Modified By</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php $no = $this->uri->segment(4) + 1; ?>
					<?php foreach ($data as $rs) : ?>
						<tr>
							<td class="middle text-center">
								<?php if ($this->pm->can_edit) : ?>
									<button type="button" class="btn btn-minier btn-warning" onclick="edit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
								<?php endif; ?>
								<?php if ($this->pm->can_delete) : ?>
									<button type="button" class="btn btn-minier btn-danger" onclick="getDelete(<?php echo $rs->id; ?>, '<?php echo $rs->name; ?>')"><i class="fa fa-trash"></i></button>
								<?php endif; ?>
							</td>
							<td class="middle no text-center"><?php echo $no; ?></td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle"><?php echo $rs->name; ?></td>
							<td class="fix-width-100 middle text-right"><?php echo $rs->draft_age; ?> days</td>
							<td class="fix-width-100 middle text-right"><?php echo $rs->reserve_age; ?> days</td>
							<td class="fix-width-100 middle text-right"><?php echo number($rs->reserve_amount, 2); ?></td>
							<td class="fix-width-80 middle text-center"><?php echo $rs->member; ?></td>
							<td class="fix-width-120 middle"><?php echo empty($rs->date_upd) ? thai_date($rs->date_add, TRUE) : thai_date($rs->date_upd, TRUE); ?></td>
							<td class="fix-width-120 middle"><?php echo empty($rs->date_upd) ? $rs->user : $rs->update_user; ?></td>								
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/sale_team.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>