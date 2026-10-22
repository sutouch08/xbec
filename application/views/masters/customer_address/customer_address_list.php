<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
    </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
				<button type="button" class="btn btn-white btn-info" onclick="syncData()"><i class="fa fa-refresh"></i> Sync</button>    	
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post">
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Customer code</label>
    <input type="text" class="form-control input-sm search-box" name="customer" value="<?php echo $customer; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Branch code</label>
    <input type="text" class="form-control input-sm search-box" name="address" value="<?php echo $address; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Address type</label>
		<select class="form-control input-sm filter" name="type">
			<option value="all">ทั้งหมด</option>
			<option value="B" <?php echo is_selected($type, "B"); ?>>Bill To</option>
			<option value="S" <?php echo is_selected($type, "S"); ?>>Ship To</option>
		</select>
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
		<button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i>  Search</button>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i>  Reset</button>
  </div>
</div>
</form>
<hr class="margin-top-15">
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-narrow border-1 min-width-950">
			<thead>
				<tr>
					<th class="fix-width-50 middle text-center">#</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="fix-width-300 middle">Name</th>
					<th class="fix-width-60 middle">Type</th>
					<th class="fix-width-150 middle">Branch</th>
					<th class="min-width-250 middle">Address</th>
					<th class="fix-width-40"></th>
				</tr>
			</thead>
			<tbody>
		<?php if(!empty($data)) : ?>
			<?php $no = $this->uri->segment($this->segment) + 1; ?>
			<?php foreach($data as $rs) : ?>
				<tr id="row-<?php echo $rs->id; ?>">
					<td class="middle text-center"><?php echo $no; ?></td>
					<td class="middle"><?php echo $rs->CardCode; ?></td>
					<td class="middle"><?php echo $rs->CardName; ?></td>
					<td class="middle"><?php echo ($rs->AdresType == 'B' ? 'Bill To' : 'Ship To'); ?></td>
					<td class="middle"><?php echo $rs->Address.' : '.$rs->Address3; ?></td>
					<td class="middle"><?php echo $rs->Street.' '.$rs->Block.' '.$rs->City.' '.$rs->County.' '.$rs->Country.' '.$rs->ZipCode; ?></td>
					<td class="middle text-center">
						<?php if($this->pm->can_delete) : ?>
							<button type="button" class="btn btn-minier btn-danger" onclick="removeAddress(<?php echo $rs->id; ?>, '<?php echo $rs->Address; ?>')"><i class="fa fa-trash"></i></button>
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

<script src="<?php echo base_url(); ?>scripts/masters/customer_address.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/sync_address.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
