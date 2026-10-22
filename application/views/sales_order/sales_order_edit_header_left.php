<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12 padding-5">
  <div class="form-horizontal">
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 sap-label">Customer</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" id="CardCode" class="form-control input-sm r" 
        value="<?php echo $order->CardCode; ?>" 
        onchange="recal_all_discount()" <?php echo ($order->role == 'C' ? 'disabled' : ''); ?> />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 sap-label">Name</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="CardName" class="form-control input-sm r" 
        value="<?php echo $order->CardName; ?>" <?php echo ($order->role == 'C' ? 'disabled' : ''); ?> />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 sap-label">Payment</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="payment" name="payment" onchange="recal_all_discount()">
          <?php echo select_payment_term($order->Payment); ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 sap-label">Channels</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="channels" name="channels" onchange="recal_all_discount()">
          <?php echo select_channels($order->Channels); ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 sap-label">Bill To</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="billToCode" onchange="updateBillTo()">
          <?php echo select_bill_to_code($order->CardCode, $order->PayToCode); ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12"></label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <textarea id="BillTo" class="autosize autosize-transition form-control" disabled><?php echo $order->Address; ?></textarea>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 sap-label">Ship To</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="shipToCode" onchange="updateShipTo()">
          <?php echo select_ship_to_code($order->CardCode, $order->ShipToCode); ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12"></label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <textarea id="ShipTo" class="autosize autosize-transition form-control" disabled><?php echo $order->Address2; ?></textarea>
      </div>
    </div>
  </div>
</div>
