<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-success" onclick="getReport()"><i class="fa fa-bar-chart"></i> รายงาน</button>
		<button type="button" class="btn btn-white btn-primary" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
	</div>
</div><!-- End Row -->
<hr class="hidden-print" />
<form class="hidden-print" id="reportForm" method="post" action="<?php echo $this->home; ?>/do_export">
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Item Code.</label>
			<input type="text" class="form-control input-sm" name="code" id="code" value="" />
		</div>

		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Item Description</label>
			<input type="text" class="form-control input-sm" name="name" id="name" value="" />
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>Method</label>
			<select class="form-control input-sm" name="ruleMethod" id="method">
				<option value="all">ทั้งหมด</option>
				<option value="F">Premium</option>
				<option value="N">Net Price</option>
				<option value="P">Percentage</option>
			</select>
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>Rule Status</label>
			<select class="form-control input-sm" name="ruleStatus" id="rule-status">
				<option value="1">Active</option>
				<option value="0">Inactive</option>
				<option value="all">ทั้งหมด</option>
			</select>
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>Promotion Status</label>
			<select class="form-control input-sm" name="promotionStatus" id="promotion-status">
				<option value="1">Active</option>
				<option value="0">Inactive</option>
				<option value="all">ทั้งหมด</option>
			</select>
		</div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>Start Date</label>
			<input type="text" class="form-control input-sm text-center" name="startDate" id="start-date" value="" />
		</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>End Date</label>
			<input type="text" class="form-control input-sm text-center" name="endDate" id="end-date" value="" />
		</div>
	</div>

	<input type="hidden" name="token" id="token" />
</form>

<hr class="margin-top-15 margin-bottom-15">

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-narrow border-1" style="min-width: 1340px;">
			<thead>
				<tr>
					<th class="fix-width-40 text-center">#</th>
					<th class="fix-width-100">Item Code</th>
					<th class="min-width-200">Item Desc.</th>
					<th class="fix-width-30"></th>
					<th class="fix-width-100">Rule Code</th>
					<th class="fix-width-100">Method</th>
					<th class="fix-width-200">Rule Desc.</th>
					<th class="fix-width-30"></th>
					<th class="fix-width-100">Promotion Code</th>
					<th class="fix-width-200">Promotion Desc.</th>
					<th class="fix-width-100">Start Date</th>
					<th class="fix-width-100">End Date</th>
				</tr>
			</thead>
			<tbody id="result-table">

			</tbody>
		</table>
	</div>
</div>

<script id="template" type="text/x-handlebarsTemplate">
	{{#each this}}
		{{#if nodata}}
			<tr>
				<td colspan="12" class="middle text-center font-size-24">--- NO DATA FOUND ---</td>
			</tr>
		{{else}}
			<tr>
				<td class="middle text-center no">{{no}}</td>
				<td class="middle">{{itemCode}}</td>
				<td class="middle">{{itemDescription}}</td>
				<td class="middle text-right">{{{ruleStatus}}}</td>
				<td class="middle">{{ruleCode}}</td>
				<td class="middle">{{ruleMethod}}</td>
				<td class="middle">{{ruleDescription}}</td>
				<td class="middle text-right">{{{promotionStatus}}}</td>
				<td class="middle">{{promotionCode}}</td>
				<td class="middle">{{promotionDescription}}</td>
				<td class="middle">{{startDate}}</td>
				<td class="middle">{{endDate}}</td>
			</tr>
		{{/if}}
	{{/each}}
</script>

<script>
	$('#start-date').datepicker({
		dateFormat: 'dd-mm-yy',
		onClose: function(sd) {
			$('#end-date').datepicker('option', 'minDate', sd);
		}
	});

	$('#end-date').datepicker({
		dateFormat: 'dd-mm-yy',
		onClose: function(sd) {
			$('#start-date').datepicker('option', 'maxDate', sd);
		}
	});
</script>

<script src="<?php echo base_url(); ?>scripts/report/item_promotions.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>