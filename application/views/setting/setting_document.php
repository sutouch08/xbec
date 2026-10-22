<form id="documentForm" method="post" action="<?php echo $this->home; ?>/update_config">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12"><span class="form-control left-label">Quotation</span></div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Prefix</span>
				<input type="text" class="form-control input-sm text-center prefix" name="PREFIX_QUOTATION" required value="<?php echo $PREFIX_QUOTATION; ?>" />
			</div>
		</div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Running</span>
				<select class="form-control input-sm" name="RUN_DIGIT_QUOTATION">
					<?php echo select_run_digit($RUN_DIGIT_QUOTATION); ?>
				</select>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">กำหนด Prefix และจำนวนหลักของ Running Number สำหรับเอกสาร Quotation</span>
		</div>
	</div>
	<div class="divider"></div>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12"><span class="form-control left-label">Sales Order</span></div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Prefix</span>
				<input type="text" class="form-control input-sm text-center prefix" name="PREFIX_ORDER" required value="<?php echo $PREFIX_ORDER; ?>" />
			</div>
		</div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Running</span>
				<select class="form-control input-sm" name="RUN_DIGIT_ORDER">
					<?php echo select_run_digit($RUN_DIGIT_ORDER); ?>
				</select>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">กำหนด Prefix และจำนวนหลักของ Running Number สำหรับเอกสาร Sales Order</span>
		</div>
	</div>
	<div class="divider"></div>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12"><span class="form-control left-label">Customer Order</span></div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Prefix</span>
				<input type="text" class="form-control input-sm text-center prefix" name="PREFIX_CUST_ORDER" required value="<?php echo $PREFIX_CUST_ORDER; ?>" />
			</div>
		</div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Running</span>
				<select class="form-control input-sm" name="RUN_DIGIT_CUST_ORDER">
					<?php echo select_run_digit($RUN_DIGIT_CUST_ORDER); ?>
				</select>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">กำหนด Prefix และจำนวนหลักของ Running Number สำหรับเอกสาร Customer Order</span>
		</div>
	</div>
	<div class="divider"></div>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12"><span class="form-control left-label">Discount Rule</span></div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Prefix</span>
				<input type="text" class="form-control input-sm text-center prefix" name="PREFIX_RULE" required value="<?php echo $PREFIX_RULE; ?>" />
			</div>
		</div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Running</span>
				<select class="form-control input-sm" name="RUN_DIGIT_RULE">
					<?php echo select_run_digit($RUN_DIGIT_RULE); ?>
				</select>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">กำหนด Prefix และจำนวนหลักของ Running Number สำหรับเอกสาร Discount Rule</span>
		</div>
	</div>
	<div class="divider"></div>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12"><span class="form-control left-label">Promotion</span></div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Prefix</span>
				<input type="text" class="form-control input-sm text-center prefix" name="PREFIX_POLICY" required value="<?php echo $PREFIX_POLICY; ?>" />
			</div>
		</div>
		<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6">
			<div class="input-group">
				<span class="input-group-addon">Running</span>
				<select class="form-control input-sm" name="RUN_DIGIT_POLICY">
					<?php echo select_run_digit($RUN_DIGIT_POLICY); ?>
				</select>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">กำหนด Prefix และจำนวนหลักของ Running Number สำหรับเอกสาร Promotion</span>
		</div>
	</div>
	<div class="divider"></div>
	
	<div class="row">		
		<div class="col-lg-7 col-md-9 col-sm-9 col-xs-12 padding-5 text-right">
			<?php if ($this->pm->can_edit or $this->pm->can_add) : ?>
				<button type="button" class="btn btn-sm btn-success btn-100 btn-block-xs" onClick="checkDocumentSetting()"><i class="fa fa-save"></i> บันทึก</button>
			<?php endif; ?>
		</div>		
		<div class="divider-hidden"></div>
	</div>
</form>