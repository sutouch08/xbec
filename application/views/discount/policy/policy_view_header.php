<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Code</label>
    <input type="text" class="form-control input-sm" value="<?php echo $policy->code; ?>" disabled />
  </div>

  <div class="col-lg-6-harf col-md-6-harf col-sm-6-harf col-xs-8 padding-5">
    <label>Description</label>
    <input type="text" class="form-control input-sm header-box" name="policy_name" id="policy_name" value="<?php echo $policy->name; ?>" disabled />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Start date</label>
		  <input type="text" class="form-control input-sm text-center header-box" name="start_date" id="fromDate" value="<?php echo thai_date($policy->start_date); ?>" disabled required />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>End date</label>
    <input type="text" class="form-control input-sm text-center header-box" name="end_date" id="toDate" value="<?php echo thai_date($policy->end_date); ?>" disabled required />
  </div>
	<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 padding-5">
		<label>Status</label>
		<select class="form-control input-sm" disabled>
			<option value="1" <?php echo is_selected('1', $policy->active); ?>>Active</option>
			<option value="0" <?php echo is_selected('0', $policy->active); ?>>Inactive</option>
		</select>
	</div>
</div>
<hr class="margin-top-15">
