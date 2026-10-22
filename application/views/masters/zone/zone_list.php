<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h3 class="title">
     <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    	<p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-info btn-100" onclick="syncData()">Sync</button>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post">
<div class="row">
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Bin Code</label>
    <input type="text" class="width-100 search-box" name="code" value="<?php echo $code; ?>" />
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Description</label>
    <input type="text" class="width-100 search-box" name="name" value="<?php echo $name; ?>" />
  </div>
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>Warehouse</label>
		<select class="width-100 filter" name="warehouse">
			<option value="all">ทั้งหมด</option>
			<?php echo select_warehouse($warehouse); ?>
		</select>
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
<hr class="margin-top-15 padding-5">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped border-1 min-width-800">
			<thead>
				<tr>
					<th class="fix-width-60 middle text-center">#</th>
					<th class="fix-width-250 middle">Bin code</th>
					<th class="fix-width-100 middle">Warehouse</th>
					<th class="min-width-200 middle">Description</th>
					<th class="fix-width-150 middle text-center">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($data)) : ?>
				<?php $no = $this->uri->segment($this->segment) + 1; ?>
				<?php foreach($data as $rs) : ?>
					<tr>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle"><?php echo $rs->code; ?></td>
						<td class="middle"><?php echo $rs->warehouse_code; ?></td>
						<td class="middle"><?php echo $rs->name; ?></td>
						<td class="text-center"><?php echo (empty($rs->last_sync) ? "" :thai_date($rs->last_sync, TRUE, '.')); ?></td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/zone.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
