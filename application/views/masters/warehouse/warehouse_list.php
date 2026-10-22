<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
        <button type="button" class="btn btn-white btn-info btn-100" onclick="syncData()">Sync</button>    	
    </div>
</div><!-- End Row -->
<hr />
<form id="searchForm" method="post">
<div class="row">
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Code</label>
    <input type="text" class="width-100 search-box" name="code" id="code" value="<?php echo $code; ?>" />
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Name</label>
    <input type="text" class="width-100 search-box" name="name" id="name" value="<?php echo $name; ?>" />
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Type</label>
    <select class="width-100 filter" name="type" id="type">
			<option value="all">ทั้งหมด</option>
			<option value="G" <?php echo is_selected($type, 'G'); ?>>Good</option>
			<option value="D" <?php echo is_selected($type, 'D'); ?>>Damage</option>
			<option value="L" <?php echo is_selected($type, 'L'); ?>>Lost</option>
			<option value="P" <?php echo is_selected($type, 'P'); ?>>Poor Package</option>
			<option value="W" <?php echo is_selected($type, 'W'); ?>>Waiting</option>
			<option value="S" <?php echo is_selected($type, 'S'); ?>>Show Item</option>
		</select>
  </div>
	<div class="col-xs-6 padding-5 visible-xs">&nbsp;</div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
</form>
<hr class="margin-top-15">
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
		G = Good,  D = Damage, P = Poor package, W = Waiting, L = Lost, S = Show
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped border-1 table-narrow" style="min-width:810px;">
			<thead>
				<tr>
					<th class="fix-width-60 middle text-center">#</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="min-width-200 middle">Name</th>
					<th class="fix-width-100 middl text-center">Type</th>
					<th class="fix-width-100 middl text-center">List In SO</th>
					<th class="fix-width-100 middl text-center">List In C-User</th>
					<th class="fix-width-150 middle text-center">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($data)) : ?>
				<?php $no = $this->uri->segment($this->segment) + 1; ?>
				<?php $disabled = $this->pm->can_edit ? '' : 'disabled'; ?>
				<?php foreach($data as $rs) : ?>
					<tr>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle"><?php echo $rs->code; ?></td>
						<td class="middle"><?php echo $rs->name; ?></td>
						<td class="middle text-center"><?php echo $rs->type; ?></td>
						<td class="middle text-center">
							<label>
								<input type="checkbox" class="ace" data-id="<?php echo $rs->id; ?>" onchange="toggleList($(this))" <?php echo is_checked('1', $rs->list); ?> <?php echo $disabled; ?> />
								<span class="lbl"></span>
							</label>
						</td>
						<td class="middle text-center">
							<label>
								<input type="checkbox" class="ace" data-id="<?php echo $rs->id; ?>" onchange="toggleCustomerList($(this))" <?php echo is_checked('1', $rs->customer_list); ?> <?php echo $disabled; ?> />
								<span class="lbl"></span>
							</label>
						</td>
						<td class="text-center"><?php echo (empty($rs->last_sync) ? "" :thai_date($rs->last_sync, TRUE, '.')); ?></td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/warehouse.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
