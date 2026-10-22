<div class="row">
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

			<?php if ($is_approver && $visible_gp) : ?>
				<div class="form-group">
					<label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total GP</label>
					<div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
						<input type="text" class="form-control input-sm text-right" value="<?php echo number($order->totalGP, 2); ?> %" readonly />
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 padding-5">
		<p class="margin-top-15" id="promotions-applied">
			Promotions applied :
			<?php if (!empty($promotions)) : ?>
				<?php foreach ($promotions as $promo) : ?>
					<span class="label label-info label-white middle pointer" title="<?php echo $promo->code; ?>">
						<?php echo $promo->name; ?> @row : <?php echo implode(', ', $promo->rows); ?>
					</span>
				<?php endforeach; ?>
			<?php else : ?>
				<span class="red">No promotion applied</span>
			<?php endif; ?>
		</p>
	</div>
	
	<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 padding-5 text-right">
		<div class="divider-hidden"></div>
		<?php if ($order->Status == 0 && ($is_approver or $this->_SuperAdmin)) : ?>
			<?php if ($order->must_approve == 1 && $order->Approved == 'P' && ($this->can_approve or $this->_SuperAdmin) && ($this->readOnly === FALSE or $this->_SuperAdmin)) : ?>
				<button type="button" class="btn btn-white btn-success btn-100" onclick="doApprove('<?php echo $order->code; ?>')">Approve</button>
				<button type="button" class="btn btn-white btn-danger btn-100" onclick="doReject('<?php echo $order->code; ?>')">Reject</button>
			<?php else : ?>
				<p class="red">คุณต้องมีสิทธิ์ในการอนุมัติ</p>
				<?php if (! empty($this->not_ap)) : ?>
					<?php foreach ($this->not_ap as $nap) : ?>
						<p class="red"><?php echo $nap['name']; ?> : <?php echo $nap['disc'] . ' %'; ?></p>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div class="divider"></div>

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