
	<form id="companyForm" method="post" action="<?php echo $this->home; ?>/update_config">    
  	<div class="row">
    	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">แบรนด์สินค้า</span>
      </div>
      <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">				
        <input type="text" class="form-control input-sm" name="COMPANY_NAME" id="brand" value="<?php echo $COMPANY_NAME; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">ชื่อบริษัท</span>
      </div>
      <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">
        <input type="text" class="form-control input-sm" name="COMPANY_FULL_NAME" id="cName" value="<?php echo $COMPANY_FULL_NAME; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">ที่อยู่บรรทัด 1</span>
      </div>
      <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">				
        <input type="text" class="form-control input-sm" name="COMPANY_ADDRESS1" id="cAddress1" placeholder="เลขที่ หมู่ ถนน ตำบล" value="<?php echo $COMPANY_ADDRESS1; ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">ที่อยู่บรรทัด 2</span>
      </div>
      <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">				
        <input type="text" class="form-control input-sm" name="COMPANY_ADDRESS2" id="cAddress2" placeholder="อำเภอ จังหวัด" value="<?php echo $COMPANY_ADDRESS2; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">รหัสไปรษณีย์</span>
      </div>
      <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12">				
        <input type="text" class="form-control input-sm" name="COMPANY_POST_CODE" id="postCode" value="<?php echo $COMPANY_POST_CODE; ?>" />
      </div>
      <div class="divider-hidden"></div>


			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				<span class="form-control left-label">เลขประจำตัวผู้เสียภาษี</span>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">				
				<input type="text" class="form-control input-sm" name="COMPANY_TAX_ID" id="taxID" value="<?php echo $COMPANY_TAX_ID; ?>" />
			</div>
			<div class="divider-hidden"></div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">โทรศัพท์</span>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">				
        <input type="text" class="form-control input-sm" name="COMPANY_PHONE" id="phone" value="<?php echo $COMPANY_PHONE; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">แฟกซ์</span>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">				
        <input type="text" class="form-control input-sm" name="COMPANY_FAX" id="fax" value="<?php echo $COMPANY_FAX; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">อีเมล์</span>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" class="form-control input-sm" name="COMPANY_EMAIL" id="email" value="<?php echo $COMPANY_EMAIL; ?>" />
      </div>
      <div class="divider-hidden"></div>


      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">Line</span>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" class="form-control input-sm" name="COMPANY_LINE" id="line" value="<?php echo $COMPANY_LINE; ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">Facebook</span>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" class="form-control input-sm" name="COMPANY_FACEBOOK" id="facebook" value="<?php echo $COMPANY_FACEBOOK; ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <span class="form-control left-label">Website</span>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" class="form-control input-sm" name="COMPANY_WEBSITE" id="website" value="<?php echo $COMPANY_WEBSITE; ?>" />
      </div>
      <div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>

      <div class="col-lg-9 col-md-9 col-sm-9 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 col-xs-12">
<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
        <button type="button" class="btn btn-sm btn-success btn-100 btn-block-xs" onClick="updateConfig('companyForm')"><i class="fa fa-save"></i> บันทึก</button>
<?php endif; ?>
      </div>		
      <div class="divider-hidden"></div>
  	</div><!--/ row -->
  </form>
