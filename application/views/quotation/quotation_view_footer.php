<div class="row">
	<!--- left column -->
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<div class="form-horizontal">

			<div class="form-group">
				<label class="col-lg-3 col-md-4 col-sm-4 control-label no-padding-right">Sales Employee</label>
				<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
					<input type="text" class="form-control input-sm" value="<?php echo $sale_name; ?>" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-4 col-sm-4 control-label no-padding-right">Customer Team</label>
				<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
					<input type="text" class="form-control input-sm" value="<?php echo $order->sale_team_name; ?>" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-4 col-sm-4 col-xs-12 control-label no-padding-right">Owner</label>
				<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
					<input type="text" class="form-control input-sm" value="<?php echo $owner; ?>" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-4 col-sm-4 col-xs-12 control-label no-padding-right">Remark</label>
				<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
					<textarea maxlength="254" class="form-control input-sm" readonly><?php echo $order->Comments; ?></textarea>
				</div>
			</div>
		</div>
	</div>


	<!--- right column -->
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total Before Discount</label>
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">					
					<input type="text" class="form-control input-sm text-right" value="<?php echo number($totalAmount, 2); ?>" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-6 col-md-4 col-sm-4 col-xs-3 control-label no-padding-right">Discount</label>
				<div class="col-lg-2 col-md-4 col-sm-3 col-xs-3 padding-5">
					<span class="input-icon input-icon-right">
						<input type="number" class="form-control input-sm" value="<?php echo $order->DiscPrcnt; ?>" readonly />
						<i class="ace-icon fa fa-percent"></i>
					</span>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">					
					<input type="text" class="form-control input-sm text-right" value="<?php echo number($order->DiscAmount, 2); ?>" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Tax</label>
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">					
					<input type="text" class="form-control input-sm text-right" value="<?php echo number($order->VatSum, 2); ?>" readonly />
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total</label>
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">					
					<input type="text" class="form-control input-sm text-right" value="<?php echo number($order->DocTotal, 2); ?>" readonly />
				</div>
			</div>
		</div>
	</div>

	<div class="divider-hidden"></div>
	<div class="divider-hidden"></div>
	<div class="divider-hidden"></div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<?php if (!empty($logs)) : ?>
			<?php foreach ($logs as $lg) : ?>
				<p style="font-size:12px; font-style:italic; color:#729fe1;">
					<?php echo action_name($lg->action); ?> โดย <?php echo $lg->uname; ?> วันที่ <?php echo thai_date($lg->date_upd, TRUE); ?>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

</div>