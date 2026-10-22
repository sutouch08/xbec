<?php $this->load->view('include/header'); ?>
<style>
	label {
		margin-top: 10px;
	}
</style>
<?php 
	$start_at = new DateTime($req_start);
 	$end_at = new DateTime($req_end);
 	$diff = $start_at->diff($end_at); 
	$total_seconds = ($diff->h * 3600) + ($diff->i * 60) + $diff->s + ($diff->f);
	$milliseconds = round($total_seconds * 1000);
?>
<div class="row">
	<div class="col-lg-2-harf col-md-2-harf col-sm-2 col-xs-12">
		<label>TransId</label>
		<input type="text" class="form-control input-sm" value="<?php echo $trans_id; ?>" disabled />
	</div>
	<div class="col-lg-3 col-md-3 col-sm-2-harf col-xs-12">
		<label>Date Time</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo thai_date($date_upd, TRUE); ?>" disabled />
	</div>
	<div class="col-lg-3 col-md-3-harf col-sm-3 col-xs-12">
		<label>Request at</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $start_at->format('d-m-Y H:i:s.v'); ?>" disabled />
	</div>
	<div class="col-lg-3 col-md-3-harf col-sm-3 col-xs-12">
		<label>Response at</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $end_at->format('d-m-Y H:i:s.v'); ?>" disabled />
	</div>
	<div class="col-lg-2 col-md-2 col-sm-1-harf col-xs-12">
		<label>Wait (ms)</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $milliseconds; ?>" disabled />
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<label>Api path</label>
		<input type="text" class="form-control input-sm" value="<?php echo $api_path; ?>" disabled />
	</div>
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
		<label>Code</label>
		<input type="text" class="form-control input-sm" value="<?php echo $code; ?>" disabled />
	</div>
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
		<label>Type</label>
		<input type="text" class="form-control input-sm" value="<?php echo $type; ?>" disabled />
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
		<label>Action</label>
		<input type="text" class="form-control input-sm" value="<?php echo $action; ?>" disabled />
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
		<label>Status</label>
		<input type="text" class="form-control input-sm" value="<?php echo $status; ?>" disabled />
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<label>Message</label>
		<textarea class="form-control input-sm" disabled><?php echo $message; ?></textarea>
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<label>Request Json</label>
		<pre><?php echo json_encode(json_decode($request_json ?? ''), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?></pre>
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<label>Response Json</label>
		<pre><?php echo json_encode(json_decode($response_json ?? ''), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?></pre>
	</div>
</div>

<?php $this->load->view('include/footer'); ?>