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
    <label>First Name</label>
    <input type="text" class="width-100 search-box" name="firstName" value="<?php echo $firstName; ?>" />
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Last Name</label>
    <input type="text" class="width-100 search-box" name="lastName" value="<?php echo $lastName; ?>" />
  </div>

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
<hr class="margin-top-15 padding-5">
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-narrow border-1" style="min-width:590px;">
			<thead>
				<tr>
					<th class="fix-width-60 middle text-center">#</th>
					<th class="fix-width-100 middle">First Name</th>
					<th class="fix-width-100 middle">Last Name</th>
					<th class="min-width-100 middle">Middle Name</th>
					<th class="fix-width-80 middle text-center">Status</th>
					<th class="fix-width-150 middle text-center">Last Sync</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($data)) : ?>
				<?php $no = $this->uri->segment($this->segment) + 1; ?>
				<?php foreach($data as $rs) : ?>
					<tr>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle"><?php echo $rs->firstName; ?></td>
						<td class="middle"><?php echo $rs->lastName; ?></td>
						<td class="middle"><?php echo $rs->middleName; ?></td>
						<td class="middle text-center"><?php echo is_active($rs->active); ?></td>
						<td class="text-center"><?php echo (empty($rs->last_sync) ? "" :thai_date($rs->last_sync, TRUE, '.')); ?></td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/employee.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
