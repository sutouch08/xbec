<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>	
</div><!-- End Row -->
<hr />
<form id="export-form" method="post" action="<?php echo $this->home; ?>/do_export">
<div class="row">
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>Item Code.</label>
		<input type="text" class="form-control input-sm" style="padding-right:15px;" name="item_code" id="item-code" value="" />
		<i class="fa fa-times grey pointer" style="position:absolute; right:10px; top:35px;" onclick="$('#item-code').val('').focus()" title="Clear"></i>
	</div>
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>Customer Code</label>
		<input type="text" class="form-control input-sm" style="padding-right:15px;" name="customer_code" id="customer-code" value="" />
		<i class="fa fa-times grey pointer" style="position:absolute; right:10px; top:35px;" onclick="$('#customer-code').val('').focus()" title="Clear"></i>
	</div>

	<div class="col-lg-3 col-md-3-harf col-sm-3-harf col-xs-6 padding-5">
		<label>User</label>
		<select class="form-control input-sm" name="uname" id="uname">
			<option value="all">ทั้งหมด</option>
			<?php echo select_uname(); ?>
		</select>
	</div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
		<label>Status</label>
		<select class="form-control input-sm" name="status" id="status">
			<option value="all">ทั้งหมด</option>
			<option value="-1">Draft</option>
			<option value="4">Reserved</option>
		</select>
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
		<label>&nbsp;</label>
		<button type="button" class="btn btn-white btn-xs btn-success btn-block" onclick="getReport()"><i class="fa fa-bar-chart"></i>&nbsp; รายงาน</button>
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
		<label>&nbsp;</label>
		<button type="button" class="btn btn-white btn-xs btn-primary btn-block" onclick="doExport()"><i class="fa fa-file-excel-o"></i>&nbsp; ส่งออก</button>
	</div>
</div>
 <input type="hidden" name="token" id="token">
</form>

<hr class="margin-top-15 margin-bottom-15">

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-narrow border-1" style="min-width: 1200px;">
			<thead>
				<tr>
					<th class="fix-width-40 text-center">#</th>
					<th class="fix-width-80">Date</th>
					<th class="fix-width-100">Web No.</th>
					<th class="min-width-200">Customer</th>
					<th class="fix-width-100">User</th>
					<th class="fix-width-50 text-center">Status</th>					
					<th class="fix-width-100">Item Code</th>
					<th class="fix-width-300">Item Desc.</th>					
					<th class="fix-width-100 text-right">Qty.</th>
					<th class="fix-width-100 text-right">Amount</th>
					<th class="fix-width-20"></th>
				</tr>
			</thead>
			<tbody id="result-table">

			</tbody>
		</table>
	</div>
</div>

<script id="report-template" type="text/x-handlebars-template">
	{{#each this}}
		{{#if nodata}}
			<tr>
				<td colspan="10" class="middle text-center font-size-24">--- NO DATA FOUND ---</td>
			</tr>
		{{else}}
			<tr>
				<td class="middle text-center no">{{no}}</td>
				<td class="middle">{{date}}</td>
				<td class="middle">{{webNo}}</td>
				<td class="middle">{{customerCode}}</td>
				<td class="middle">{{uname}}</td>
				<td class="middle text-center">{{status}}</td>
				<td class="middle">{{itemCode}}</td>
				<td class="middle">{{itemDescription}}</td>
				<td class="middle text-right">{{qty}}</td>
				<td class="middle text-right">{{amount}}</td>
				<td class="middle text-center"></td>
			</tr>
		{{/if}}
	{{/each}}
</script>

<script>
	$('#uname').select2();
</script>

<script src="<?php echo base_url(); ?>scripts/report/item_reserved.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>