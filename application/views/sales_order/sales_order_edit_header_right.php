<div class="col-lg-4 col-md-5 col-sm-5 col-xs-12 padding-5">
	<div class="form-horizontal">
		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Web Order</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<input type="text" id="code" class="form-control input-sm" value="<?php echo $order->code; ?>" disabled />
			</div>
		</div>

		<?php if (! empty($order->SqNo)) : ?>
			<div class="form-group">
				<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">SQ No.</label>
				<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
					<div class="input-group">
						<input type="text" id="sqNo" class="form-control input-sm" value="<?php echo $order->SqNo; ?>" disabled />
						<span class="input-group-addon pointer" onclick="viewSQ('<?php echo $order->SqNo; ?>')"><i class="fa fa-info-circle"></i></span>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Status</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<input type="text" id="status" class="form-control input-sm" value="<?php echo order_status_name($order->Status, $order->Approved); ?>" disabled />				
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Posting Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
					<input type="text" id="TextDate" class="form-control input-sm r" value="<?php echo thai_date($order->TextDate, FALSE); ?>" readonly />
					<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Delivery Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
					<input type="text" id="ShipDate" class="form-control input-sm r" value="<?php echo thai_date($order->DocDueDate, FALSE); ?>" readonly />
					<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>


		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Document Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
					<input type="text" id="DocDate" class="form-control input-sm r" value="<?php echo thai_date($order->DocDate, FALSE); ?>" readonly onchange="recal_all_discount()" />
					<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-5 col-md-4 col-sm-4 col-xs-12 sap-label">Projects</label>
			<div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
				<select class="form-control input-sm r" id="projects" name="projects">
					<option value="">Please Select</option>
					<?php echo select_projects($order->projectCode); ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-5 col-md-4 col-sm-4 col-xs-12 sap-label">แผนก</label>
			<div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
				<select class="form-control input-sm r" id="dimCode5" name="dimCode5">
					<option value="">Please Select</option>
					<?php echo select_cost_center(5, $order->dimCode5); ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Available Credit <span class="font-size-11"> (exclude this order)</span></label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<input type="text" id="available-credit" class="form-control input-sm text-right" value="<?php echo number($availableCredit, 2); ?>" disabled />
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 sap-label">Available Reserve <span class="font-size-11"> (exclude this order)</span></label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<input type="text" id="available-reserve" class="form-control input-sm text-right" value="<?php echo number($availableReserve, 2); ?>" disabled />
			</div>
		</div>
	</div>
</div>