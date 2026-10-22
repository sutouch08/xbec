<?php
$open     = $CLOSE_SYSTEM == 0 ? 'btn-success' : '';
$close    = $CLOSE_SYSTEM == 1 ? 'btn-danger' : '';
$freze    = $CLOSE_SYSTEM == 2 ? 'btn-warning' : '';
$strongOn = $USE_STRONG_PWD == 1 ? 'btn-primary' : '';
$strongOff = $USE_STRONG_PWD == 0 ? 'btn-primary' : '';
$disable = $this->_SuperAdmin ? "" : "disabled";
?>

<form id="systemForm" method="post" action="<?php echo $this->home; ?>/update_config">
  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><span class="form-control left-label">ปิดระบบ</span></div>
    <div class="col-lg-3-harf col-md-5 col-sm-6 col-xs-12">
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm <?php echo $open; ?>" style="width:33%;" id="btn-open" onClick="openSystem()" <?php echo $disable; ?>>เปิด</button>
        <button type="button" class="btn btn-sm <?php echo $close; ?>" style="width:33%;" id="btn-close" onClick="closeSystem()" <?php echo $disable; ?>>ปิด</button>
        <button type="button" class="btn btn-sm <?php echo $freze; ?>" style="width:34%" id="btn-freze" onclick="frezeSystem()" <?php echo $disable; ?>>ดูอย่างเดียว</button>
      </div>

      <input type="hidden" name="CLOSE_SYSTEM" id="closed" value="<?php echo $CLOSE_SYSTEM; ?>" />
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <span class="help-block">กรณีปิดระบบจะไม่สามารถเข้าใช้งานระบบได้ในทุกส่วน กรณีดูอย่างเดียวจะสามารถเข้าใช้งานระบบได้ แต่ไม่สามารถบันทึกข้อมูลใดๆได้ โปรดใช้ความระมัดระวังในการกำหนดค่านี้</span>
    </div>
  </div>
  <div class="divider"></div>

  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6"><span class="form-control left-label">Strong Password</span></div>
    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-6 text-right-xs">
      <label style="padding-top:5px; margin-bottom:0px;">
        <input class="ace ace-switch ace-switch-7" data-name="USE_STRONG_PWD" type="checkbox" value="1" <?php echo is_checked($USE_STRONG_PWD, '1'); ?> onchange="toggleOption($(this))" />
        <span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
      </label>
      <input type="hidden" name="USE_STRONG_PWD" id="use-strong-pwd" value="<?php echo $USE_STRONG_PWD; ?>" />
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <span class="help-block">เมื่อเปิดใช้งาน การกำหนดรหัสผ่านจะต้องประกอบด้วย ตัวอักษรภาษาอังกฤษ พิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข และสัญลักษณ์พิเศษ อย่างน้อย อย่างล่ะ 1 ตัว และต้องมีความยาว 8 ตัวอักษรขึ้นไป</span>
    </div>
  </div>
  <div class="divider"></div>

  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-8"><span class="form-control left-label">Password Age</span></div>
    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4">
      <input type="number" class="form-control input-sm text-center" name="USER_PASSWORD_AGE" id="pwd-age" value="<?php echo $USER_PASSWORD_AGE; ?>" />
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <span class="help-block">กำหนดอายุของรหัสผ่าน(วัน) User จำเป็นต้องเปลี่ยนรหัสผ่านหากรหัสผ่านหมดอายุ หากไม่ต้องการให้รหัสผ่านหมดอายุ ให้กำหนดค่าเป็น 0</span>
    </div>
  </div>
  <div class="divider"></div>

  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"><span class="form-control left-label">Interface API Endpoint</span></div>
    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
      <input type="text" class="form-control input-sm input-xxlarge" name="SAP_API_HOST" id="" value="<?php echo $SAP_API_HOST; ?>" />
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <span class="help-block">กำหนด URL endpoint สำหรับการ Interface กับ ระบบ SAP</span>
    </div>
  </div>
  <div class="divider"></div>

  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6"><span class="form-control left-label">Test Interface</span></div>
    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-6 text-right-xs">
      <label style="padding-top:5px; margin-bottom:0px;">
        <input class="ace ace-switch ace-switch-7" data-name="TEST_INTERFACE" type="checkbox" value="1" <?php echo is_checked($TEST_INTERFACE, '1'); ?> onchange="toggleOption($(this))" />
        <span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
      </label>      
      <input type="hidden" name="TEST_INTERFACE" id="test-interface" value="<?php echo $TEST_INTERFACE; ?>" />
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <span class="help-block">เมื่อเปิดใช้งาน ระบบจะไม่ส่ง interface เข้า SAP</span>
    </div>
  </div>
  <div class="divider"></div>

  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6"><span class="form-control left-label">Logs Interface</span></div>
    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-6 text-right-xs">
      <label style="padding-top:5px; margin-bottom:0px;">
        <input class="ace ace-switch ace-switch-7" data-name="LOGS_JSON" type="checkbox" value="1" <?php echo is_checked($LOGS_JSON, '1'); ?> onchange="toggleOption($(this))" />
        <span class="lbl margin-left-0" data-lbl="OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ON"></span>
      </label>      
      <input type="hidden" name="LOGS_JSON" id="logs-json" value="<?php echo $LOGS_JSON; ?>" />
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <span class="help-block">เมื่อเปิดใช้งาน ระบบจะบันทึกข้อมูล interface เก็บไว้</span>
    </div>
  </div>
  <div class="divider"></div>  

  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-8 col-sm-offset-4 col-lg-offset-3 col-md-offset-3 col-xs-12">
      <?php if ($this->pm->can_add or $this->pm->can_edit) : ?>
        <button type="button" class="btn btn-sm btn-success btn-100 btn-block-xs" onClick="updateConfig('systemForm')"><i class="fa fa-save"></i> บันทึก</button>
      <?php endif; ?>
    </div>  
  </div>
  <div class="divider-hidden"></div>

  </div><!--/row-->
</form>