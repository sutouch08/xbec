<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<?php if ($this->pm->can_add) : ?>
			<button type="button" class="btn btn-white btn-success" onclick="addNew()"><i class="fa fa-plus"></i> &nbsp; Add new</button>
		<?php endif; ?>
	</div>
</div><!-- End Row -->
<hr class="padding-5" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
			<label>Code</label>
			<input type="text" class="form-control input-sm search-box" name="code" value="<?php echo $code; ?>" />
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
			<label>Name</label>
			<input type="text" class="form-control input-sm search-box" name="name" value="<?php echo $name; ?>" />
		</div>

		<div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5">
			<label>Parent</label>
			<select class="form-control input-sm filter" name="parent">
				<option value="all">ทั้งหมด</option>
				<?php echo select_parent($parent); ?>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
			<label>Level</label>
			<select class="form-control input-sm filter" name="level">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $level); ?>>Level 1</option>
				<option value="2" <?php echo is_selected('2', $level); ?>>Level 2</option>
				<option value="3" <?php echo is_selected('3', $level); ?>>Level 3</option>
				<option value="4" <?php echo is_selected('4', $level); ?>>Level 4</option>
				<option value="5" <?php echo is_selected('5', $level); ?>>Level 5</option>
			</select>
		</div>

		<div class="col-lg-2 col-md-2 col-sm-2-harf col-xs-6 padding-5">
			<label>Status</label>
			<select class="form-control input-sm filter" name="active">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $active); ?>>Active</option>
				<option value="0" <?php echo is_selected('0', $active); ?>>Inactive</option>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>
	</div>
	<input type="hidden" name="search" value="1" />
</form>
<hr class="margin-top-15 padding-5">
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive padding-5">
		<table class="table table-striped border-1 table-narrow" style="min-width:890px;">
			<thead>
				<tr>
					<th class="fix-width-50 middle text-center">#</th>
					<th class="fix-width-60 middle text-center">img</th>
					<th class="fix-width-80 middle">Code</th>
					<th class="min-width-250 middle">Name</th>
					<th class="fix-width-60 middle text-center">Level</th>
					<th class="fix-width-250 middle">Parent</th>
					<th class="fix-width-60 middle text-center">Status</th>
					<th class="fix-width-80 middle"></th>
				</tr>
			</thead>
			<tbody>
				<?php if (! empty($data)) : ?>
					<?php $pageNo = get_zero($this->uri->segment($this->segment)); ?>
					<?php $no = $pageNo + 1; ?>
					<?php foreach ($data as $rs) : ?>
						<tr>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle text-center">
								<img src="<?php echo get_category_path($rs->code); ?>" width="60" height="60" />
							</td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle"><?php echo $rs->name; ?></td>
							<td class="middle text-center"><?php echo $rs->level; ?></td>
							<td class="middle"><?php echo $rs->parent; ?></td>
							<td class="middle text-center">
								<label>
									<input type="checkbox" class="ace" onchange="setActive($(this))" data-id="<?php echo $rs->id; ?>" <?php echo is_checked('1', $rs->active); ?> />
									<span class="lbl"></span>
								</label>
							</td>
							<td class="middle">
								<button type="button" class="btn btn-mini btn-info" onclick="viewDetail(<?php echo $rs->id; ?>, <?php echo $pageNo; ?>)"><i class="fa fa-eye"></i></button>
								<?php if ($this->pm->can_edit) : ?>
									<button type="button" class="btn btn-mini btn-warning" onclick="edit(<?php echo $rs->id; ?>, <?php echo $pageNo; ?>)"><i class="fa fa-pencil"></i></button>
								<?php endif; ?>
							</td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/product_category.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>