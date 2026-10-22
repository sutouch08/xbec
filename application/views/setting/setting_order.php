<form id="orderForm" method="post" action="<?php echo $this->home; ?>/update_config">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6"><span class="form-control left-label">Credit Limit</span></div>
		<div class="col-lg-9 col-md-8 col-sm-8 col-xs-6 text-right-xs">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="CREDIT_LIMIT" type="checkbox" value="1" <?php echo is_checked($CREDIT_LIMIT, '1'); ?> onchange="toggleOption($(this))" />
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="CREDIT_LIMIT" id="credit-limit" value="<?php echo $CREDIT_LIMIT; ?>" />
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">เมื่อเปิดใช้งาน จะไม่อนุญาติให้สั่งซื้อสินค้าเกินกว่าเครดิตคงเหลือได้</span>
		</div>
	</div>
	<div class="divider"></div>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6"><span class="form-control left-label">Allow Reserve</span></div>
		<div class="col-lg-9 col-md-8 col-sm-8 col-xs-6 text-right-xs">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="ALLOW_RESERVE" type="checkbox" value="1" <?php echo is_checked($ALLOW_RESERVE, '1'); ?> onchange="toggleOption($(this))" />
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="ALLOW_RESERVE" id="allow-reserve" value="<?php echo $ALLOW_RESERVE; ?>" />
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">เมื่อเปิดใช้งาน จะอนุญาติให้จองสินค้าได้</span>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6"><span class="form-control left-label">Limit Reserved</span></div>
		<div class="col-lg-9 col-md-8 col-sm-8 col-xs-6 text-right-xs">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="LIMIT_RESERVE" type="checkbox" value="1" <?php echo is_checked($LIMIT_RESERVE, '1'); ?> onchange="toggleOption($(this))" />
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="LIMIT_RESERVE" id="limit-reserve" value="<?php echo $LIMIT_RESERVE; ?>" />
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">เมื่อเปิดใช้งาน จะไม่อนุญาติให้จองสินค้าเกินกว่ามูลค่าที่กำหนดได้</span>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6"><span class="form-control left-label">Auto Cancel Reserve</span></div>
		<div class="col-lg-9 col-md-8 col-sm-8 col-xs-6 text-right-xs">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="AUTO_CANCEL_RESERVE" type="checkbox" value="1" <?php echo is_checked($AUTO_CANCEL_RESERVE, '1'); ?> onchange="toggleOption($(this))" />
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="AUTO_CANCEL_RESERVE" id="auto-cancel-reserve" value="<?php echo $AUTO_CANCEL_RESERVE; ?>" />
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">เปิด/ปิด การยกเลิกอัตโนมัติสำหรับรออเดอร์ที่อยู่ในสถานะ Reserve</span>
		</div>
	</div>
	<div class="divider"></div>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6"><span class="form-control left-label">Auto Cancel Draft</span></div>
		<div class="col-lg-9 col-md-8 col-sm-8 col-xs-6 text-right-xs">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="AUTO_CANCEL_DRAFT" type="checkbox" value="1" <?php echo is_checked($AUTO_CANCEL_DRAFT, '1'); ?> onchange="toggleOption($(this))" />
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="AUTO_CANCEL_DRAFT" id="auto-cancel-draft" value="<?php echo $AUTO_CANCEL_DRAFT; ?>" />
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">เปิด/ปิด การยกเลิกอัตโนมัติสำหรับรออเดอร์ที่อยู่ในสถานะ Draft</span>
		</div>
	</div>	
	<div class="divider"></div>
	
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-8"><span class="form-control left-label">Show Stock To Customer</span></div>
		<div class="col-lg-9 col-md-8 col-sm-8 col-xs-4 text-right-xs">
			<label style="padding-top:5px; margin-bottom:0px;">
				<input class="ace ace-switch ace-switch-7" data-name="GET_STOCK_ON_CUSTOMER_ORDER" type="checkbox" value="1" <?php echo is_checked($GET_STOCK_ON_CUSTOMER_ORDER, '1'); ?> onchange="toggleOption($(this))" />
				<span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
			</label>
			<input type="hidden" name="GET_STOCK_ON_CUSTOMER_ORDER" id="available-stock" value="<?php echo $GET_STOCK_ON_CUSTOMER_ORDER; ?>" />
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">เมื่อเปิดใช้งาน ระบบจะดึงสต็อกคงเหลือมาคำนวนยอด Avalible ทำให้ระบบแสดงผลช้าลงเป็นอย่างมาก</span>
		</div>
	</div>
	<div class="divider"></div>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-9"><span class="form-control left-label">Limit SKU per customer order</span></div>
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
			<input type="number" class="form-control input-sm text-center" id="CUSTOMER_ORDER_LIMIT_SKU" name="CUSTOMER_ORDER_LIMIT_SKU" value="<?php echo $CUSTOMER_ORDER_LIMIT_SKU; ?>" />
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<span class="help-block">จำกัดจำนวน SKU สูงสุด / 1 ออเดอร์ (customer order) หากเกินกว่าที่กำหนดจะสร้างส่วนที่เกินเป็น ออเดอร์ใหม่ (เฉพาะ C-user) หากไม่ใช้งานให้กำหนดเป็น 0</span>
		</div>
	</div>


	<div class="divider-hidden"></div>
	<div class="divider-hidden"></div>
	<div class="divider-hidden"></div>
	<div class="row">
		<div class="col-lg-9 col-md-8 col-sm-8 col-lg-offset-3 col-md-offset-4 col-sm-offset-4 col-xs-12">
			<?php if ($this->pm->can_add or $this->pm->can_edit) : ?>
				<button type="button" class="btn btn-sm btn-success btn-100 btn-block-xs" onClick="updateConfig('orderForm')"><i class="fa fa-save"></i> บันทึก</button>
			<?php endif; ?>
		</div>
		<div class="divider-hidden"></div>

	</div><!--/row-->
</form>