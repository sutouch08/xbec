<?php $this->load->view('include/header'); ?>
<div class="row hidden-print">
	<div class="col-sm-8 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
    </div>
		<div class="col-sm-4 padding-5">
			<p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-bar-chart"></i> รายงาน</button>
				<button type="button" class="btn btn-sm btn-primary" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
			</p>
		</div>
</div><!-- End Row -->
<hr class="hidden-print"/>
<form class="hidden-print" id="reportForm" method="post" action="<?php echo $this->home; ?>/do_export">
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Web No.</label>
      <input type="text" class="width-100 search-box" name="code" id="web-code" value="" />
    </div>

  	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
  		<label>Customer Code</label>
  		<input type="text" class="width-100 search-box" name="customer" id="customer" value="" />
  	</div>

  	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
  		<label>SO No.</label>
      <input type="text" class="width-100 search-box" name="soCode" id="so-code"  value="" />
  	</div>
  	<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
      <label>Sale Employee</label>
  		<select class="width-100 filter" name="sale_id" id="sale_id">
  			<option value="all">ทั้งหมด</option>
  			<?php echo select_sale(); ?>
  		</select>
    </div>

  	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
  		<label>INV Date</label>
  		<div class="input-daterange input-group width-100">
  			<input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo date('01-m-Y'); ?>" />
  			<input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo date('t-m-Y'); ?>" />
  		</div>
  	</div>
  </div>
  <input type="hidden" name="token" id="token" />
</form>

<hr class="margin-top-15 margin-bottom-15">

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive" id="result-window" style="overflow:auto;">
		<table class="table table-bordered tableFixHead" style="min-width:1300px;">
			<thead>
				<tr class="font-size-11">
					<th class="fix-width-40 fix-header" style="font-size:11px; font-weight:normal;">#</th>
					<th class="fix-width-80 fix-header" style="font-size:11px; font-weight:normal;">Code</th>
					<th class="min-width-300 fix-header" style="font-size:11px; font-weight:normal;">Customer</th>
					<th class="fix-width-120 fix-header" style="font-size:11px; font-weight:normal;">Sales Employee</th>
					<th class="fix-width-80 fix-header" style="font-size:11px; font-weight:normal;">WO#</th>
					<th class="fix-width-60 fix-header" style="font-size:11px; font-weight:normal;">Prefix</th>
					<th class="fix-width-80 fix-header" style="font-size:11px; font-weight:normal;">INV#</th>
					<th class="fix-width-80 fix-header" style="font-size:11px; font-weight:normal;">INV Date</th>
					<th class="fix-width-100 fix-header" style="font-size:11px; font-weight:normal;">Sub Total</th>
					<th class="fix-width-100 fix-header" style="font-size:11px; font-weight:normal;">Vat Total</th>
					<th class="fix-width-100 fix-header" style="font-size:11px; font-weight:normal;">Grand Total</th>
					<th class="fix-width-80 fix-header" style="font-size:11px; font-weight:normal;">SO#</th>
					<th class="fix-width-80 fix-header" style="font-size:11px; font-weight:normal;">PK#</th>
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
      <tr class="font-size-10">
        <td colspan="13" class="middle text-center font-size-24">--- NO DATA FOUND ---</td>
      </tr>
    {{else}}
      <tr class="font-size-10">
        <td class="middle text-center">{{no}}</td>
				<td class="middle">{{customerCode}}</td>
				<td class="middle">{{customerName}}</td>
				<td class="middle">{{saleEmployee}}</td>
        <td class="middle">{{U_WEBORDER}}</td>
				<td class="middle text-center">{{ivPrefix}}</td>
				<td class="middle text-center">{{ivCode}}</td>
				<td class="middle text-center">{{ivDate}}</td>
				<td class="middle text-right">{{subTotal}}</td>
				<td class="middle text-right">{{vatTotal}}</td>
				<td class="middle text-right">{{grandTotal}}</td>
				<td class="middle">{{soPrefix}} {{soCode}}</td>
				<td class="middle">{{doPrefix}} {{doCode}}</td>
			</tr>
    {{/if}}
	{{/each}}
</script>

<script>
  $('#sale_id').select2();
	$('#fromDate').datepicker({
		dateFormat:'dd-mm-yy',
		onClose:function(sd) {
			$('#toDate').datepicker('option', 'minDate', sd);
		}
	})

	$('#toDate').datepicker({
		dateFormat:'dd-mm-yy',
		onClose:function(sd) {
			$('#fromDate').datepicker('option', 'maxDate', sd);
		}
	})
</script>

<script src="<?php echo base_url(); ?>scripts/report/check_transection_document.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
