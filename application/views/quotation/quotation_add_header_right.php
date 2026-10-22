<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 padding-5 last">
	<div class="form-horizontal">
		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Web Order</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<input type="text" id="code" class="form-control input-sm" value="" disabled />
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Document Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
					<input type="text" id="DocDate" class="form-control input-sm r" value="<?php echo date('d-m-Y'); ?>" readonly />
					<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>

		<div class="form-group hide">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Posting Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
					<input type="text" id="TextDate" class="form-control input-sm r" value="<?php echo date('d-m-Y'); ?>" readonly />
					<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Valid Until</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
					<input type="text" id="ShipDate" class="form-control input-sm r" value="<?php echo date('d-m-Y'); ?>" readonly />
					<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-5 col-md-4 col-sm-4 col-xs-12 sap-label">Projects</label>
			<div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
				<select class="form-control input-sm" id="projects" name="projects">
					<option value="">Please Select</option>
					<?php echo select_projects(); ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-5 col-md-4 col-sm-4 col-xs-12 sap-label">แผนก</label>
			<div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
				<select class="form-control input-sm r" id="dimCode5" name="dimCode5">
					<option value="">Please Select</option>
					<?php echo select_cost_center(5); ?>
				</select>
			</div>
		</div>		
	</div>
</div>