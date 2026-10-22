	<form id="sapForm" method="post" action="<?php echo $this->home; ?>/update_config">
	  <div class="row">
	    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-8"><span class="form-control left-label">Default Currency</span></div>
	    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
	      <input type="text" class="form-control input-sm" name="CURRENCY" value="<?php echo $DEFAULT_CURRENCY; ?>" />
	    </div>
	    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	      <span class="help-block">กำหนดสกุลเงินเริ่มต้นของระบบ</span>
	    </div>
	  </div>
	  <div class="divider"></div>

	  <div class="row">
	    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-8"><span class="form-control left-label">Default VAT Code</span></div>
	    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
	      <input type="text" class="form-control input-sm" name="SALE_VAT_CODE" value="<?php echo $SALE_VAT_CODE; ?>" />
	    </div>
	    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	      <span class="help-block">กำหนดรหัสภาษีมูลค่าเพิ่มเริ่มต้นของระบบ</span>
	    </div>
	  </div>
	  <div class="divider"></div>

	  <div class="row">
	    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-8"><span class="form-control left-label">Default VAT Rate</span></div>
	    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
	      <input type="text" class="form-control input-sm" name="SALE_VAT_RATE" value="<?php echo $SALE_VAT_RATE; ?>" />
	    </div>
	    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	      <span class="help-block">กำหนดอัตราภาษีมูลค่าเพิ่มเริ่มต้นของระบบ</span>
	    </div>
	  </div>
	  <div class="divider"></div>

	  <div class="row">
	    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><span class="form-control left-label">Default Warehouse</span></div>
	    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
	      <select class="width-100" id="default-warehouse" name="DEFAULT_WAREHOUSE">
	        <?php echo select_warehouse($DEFAULT_WAREHOUSE); ?>
	      </select>
	    </div>
	    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	      <span class="help-block">กำหนดคลังสินค้าหรือโกดังเริ่มต้นของระบบ</span>
	    </div>
	  </div>
	  <div class="divider"></div>

	  <div class="row">
	    <div class="col-lg-9 col-md-9 col-sm-8 col-sm-offset-4 col-lg-offset-3 col-md-offset-3 col-xs-12">
	      <?php if ($this->pm->can_add or $this->pm->can_edit) : ?>
	        <button type="button" class="btn btn-sm btn-success btn-100 btn-block-xs" onClick="updateConfig('sapForm')"><i class="fa fa-save"></i> บันทึก</button>
	      <?php endif; ?>
	    </div>
	  </div>
	</form>