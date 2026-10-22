<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"> <?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<?php if ($this->pm->can_add) : ?>
			<button type="button" class="btn btn-white btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add new</button>
		<?php endif; ?>
	</div>
</div><!-- End Row -->
<hr class="" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Code/Name</label>
			<input type="text" class="form-control input-sm search-box" name="code" value="<?php echo $code; ?>" />
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
<hr class="margin-top-15" />

<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-striped table-narrow border-1" style="min-width:700px;">
			<thead>
				<tr>
					<th class="fix-width-40"></th>
					<th class="fix-width-60 middle text-center">#</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="min-width-200 middle">Name</th>
					<th class="fix-width-60 middle text-center">Position</th>
					<th class="fix-width-60 middle text-center">Status</th>
					<th class="fix-width-60 middle text-center">Default</th>
					<th class="fix-width-150 middle text-center">Last Modified</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php $pageNo = get_zero($this->uri->segment($this->segment)); ?>
					<?php $no = $pageNo + 1; ?>
					<?php foreach ($data as $rs) : ?>
						<tr>
							<td class="middle text-center">
								<?php if ($this->pm->can_edit) : ?>
									<button type="button" class="btn btn-minier btn-warning" onclick="edit(<?php echo $rs->id; ?>, <?php echo $pageNo; ?>)"><i class="fa fa-pencil"></i></button>
								<?php endif; ?>
							</td>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle"><?php echo $rs->name; ?></td>
							<td class="middle text-center"><?php echo $rs->position; ?></td>
							<td class="middle text-center"><?php echo is_active($rs->active); ?></td>
							<td class="middle text-center"><?php echo is_active($rs->is_default, FALSE); ?></td>							
							<td class="middle"><?php echo (empty($rs->date_upd) ? thai_date($rs->date_add, TRUE) : thai_date($rs->date_upd, TRUE)); ?></td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/channels.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>