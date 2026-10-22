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
<hr class="margin-bottom-10" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
			<label>Code</label>
			<input type="text" class="form-control input-sm search-box" name="code" value="<?php echo $code; ?>" />
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
			<label>List in SO</label>
			<select class="form-control input-sm filter" name="list">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected($list, '1'); ?>>Yes</option>
				<option value="0" <?php echo is_selected($list, '0'); ?>>No</option>
			</select>
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
					<th class="fix-width-150 middle">Code</th>
					<th class="fix-width-150 middle text-center">List in SO</th>
					<th class="min-width-100 middle text-center"></th>
					<th class="fix-width-150 middle text-center">Last update</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php $no = $this->uri->segment($this->segment) + 1; ?>
					<?php foreach ($data as $rs) : ?>
						<?php $disabled = $this->pm->can_edit ? '' : 'disabled'; ?>
						<tr>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle text-center">
								<label>
									<input type="checkbox" class="ace"
										data-id="<?php echo $rs->id; ?>"
										onchange="toggleList($(this))"
										<?php echo is_checked('1', $rs->listed); ?>
										<?php echo $disabled; ?> />
									<span class="lbl"></span>
								</label>
							</td>
							<td></td>
							<td class="middle text-center"><?php echo thai_date($rs->date_upd, TRUE); ?></td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/quota.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>