<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-12 padding-5">
		<h4 class="title"><?php echo $this->title; ?></h4>
	</div>
</div><!-- End Row -->
<hr class="padding-5" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>เลขที่/รหัส</label>
			<input type="text" class="form-control input-sm search-box" name="code" value="<?php echo $code; ?>" />
		</div>		
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>Status</label>
			<select class="width-100 filter" name="status">
				<option value="all">All</option>
				<option value="success" <?php echo is_selected('success', $status); ?>>SUCCESS</option>
				<option value="failed" <?php echo is_selected('failed', $status); ?>>FAILED</option>
				<option value="test" <?php echo is_selected('test', $status); ?>>TEST</option>
			</select>
		</div>
		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Type</label>
			<select class="width-100 filter" name="type">
				<option value="all">All</option>
				<option value="SQ" <?php echo is_selected('SQ', $type); ?>>Sales Quotation</option>
				<option value="SO" <?php echo is_selected('SO', $type); ?>>Sales Order</option>
				<option value="pd-item" <?php echo is_selected('pd-item', $type); ?>>Product Item</option>
				<option value="pd-model" <?php echo is_selected('pd-model', $type); ?>>Product Model</option>
				<option value="pd-category" <?php echo is_selected('pd-category', $type); ?>>Product Category</option>
				<option value="pd-type" <?php echo is_selected('pd-type', $type); ?>>Product Type</option>
				<option value="pd-brand" <?php echo is_selected('pd-brand', $type); ?>>Product Brand</option>
				<option value="customer" <?php echo is_selected('customer', $type); ?>>Customer</option>
				<option value="customer-area" <?php echo is_selected('customer-area', $type); ?>>Customer Area</option>
				<option value="customer-grade" <?php echo is_selected('customer-grade', $type); ?>>Customer Grade</option>
				<option value="customer-type" <?php echo is_selected('customer-type', $type); ?>>Customer Type</option>
				<option value="customer-region" <?php echo is_selected('customer-region', $type); ?>>Customer Region</option>
			</select>
		</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
			<label>Action</label>
			<select class="width-100 filter" name="action">
				<option value="all">All</option>
				<option value="create" <?php echo is_selected('create', $action); ?>>CREATE</option>
				<option value="update" <?php echo is_selected('update', $action); ?>>UPDATE</option>
				<option value="cancel" <?php echo is_selected('cancel', $action); ?>>CANCEL</option>				
			</select>
		</div>

		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
			<label>Date</label>
			<div class="input-daterange input-group">
				<input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
				<input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
			</div>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1 col-xs-3 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="submit" class="btn btn-xs btn-primary btn-block" onclick="getSearch()">Search</button>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1 col-xs-3 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Reset</button>
		</div>

	</div>
	<input type="hidden" name="search" value="1" />
</form>
<hr class="margin-top-15 padding-5" />
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive padding-5">
		<table class="table table-striped border-1 table-narrow" style="min-width:1020px;">
			<thead>
				<tr class="font-size-11">
					<th class="fix-width-40"></th>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-150 middle">Date</th>
					<th class="fix-width-150 middle">Code/Document No.</th>					
					<th class="fix-width-80 middle text-center">Type</th>
					<th class="fix-width-80 middle text-center">Action</th>
					<th class="fix-width-80 middle text-center">Status</th>
					<th class="fix-width-250 middle">API Path</th>
					<th class="min-width-150 middle">Message</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($logs)) : ?>
					<?php $no = $this->uri->segment($this->segment) + 1; ?>					
					<?php foreach ($logs as $rs) : ?>						
						<tr class="font-size-11">
							<td class="middle"><button type="button" class="btn btn-minier btn-info" onclick="viewDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button></td>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle"><?php echo thai_date($rs->date_upd, TRUE, '/'); ?></td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle text-center"><?php echo $rs->type; ?></td>
							<td class="middle text-center"><?php echo $rs->action; ?></td>
							<td class="middle text-center"><?php echo $rs->status; ?></td>
							<td class="middle"><?php echo $rs->api_path; ?></td>
							<td class="middle"><?php echo $rs->message; ?></td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>


<script>	
	$("#fromDate").datepicker({
		dateFormat: 'dd-mm-yy',
		onClose: function(ds) {
			$("#toDate").datepicker("option", "minDate", ds);
		}
	});

	$("#toDate").datepicker({
		dateFormat: 'dd-mm-yy',
		onClose: function(ds) {
			$("#fromDate").datepicker("option", "maxDate", ds);
		}
	});
	
	function viewDetail(id) {
		const url = `${HOME}/view_detail/${id}?nomenu&nonavbar`;
		const width = 800;
		const height = 900;
		const left = (window.innerWidth - width) / 2;
		window.open(url, "_blank", `width=${width},height=${height},left=${left},scrollbars=yes,resizable=yes`);
	}
</script>


<?php $this->load->view('include/footer'); ?>