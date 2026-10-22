<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 padding-5">
  <div class="form-horizontal">
    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12 sap-label">Customer</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" id="CardCode" class="form-control input-sm r" value="" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12 sap-label">Name</label>
      <div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
        <input type="text" id="CardName" class="form-control input-sm r" value="" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12 sap-label">Contact Person</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <input type="text" id="contact" class="form-control input-sm" maxlength="100" value="" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12 sap-label">Phone Number</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <input type="text" id="phone" class="form-control input-sm" maxlength="50" value="" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12 sap-label">Payment</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="payment" name="payment">
          <?php echo select_payment_term(); ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12 sap-label">Channels</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="channels" name="channels">
          <?php echo select_channels($default_channels); ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12 sap-label">Bill To</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="billToCode">
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12"></label>
      <div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
        <textarea id="BillTo" class="autosize autosize-transition form-control"></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12 sap-label">Ship To</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="shipToCode" onchange="get_address_ship_to()">
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2-harf col-md-3 col-sm-3 col-xs-12"></label>
      <div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
        <textarea id="ShipTo" class="autosize autosize-transition form-control"></textarea>
      </div>
    </div>
  </div>
</div>