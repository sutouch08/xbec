<?php $this->load->view('include/header'); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.autosize.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sortable.min.js"></script>
<?php $this->load->view('sales_order/style'); ?>
<div class="row">
  <div class="col-sm-6 col-xs-6 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
  <div class="col-sm-6 col-xs-6 padding-5">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-white btn-default" onclick="leave()"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
    </p>
  </div>
</div><!-- End Row -->
<hr class="padding-5" />
<?php $this->load->view('sales_order/sales_order_add_header'); ?>
<?php $this->load->view('sales_order/sales_order_add_detail'); ?>
<?php $this->load->view('sales_order/sales_order_add_footer'); ?>

<input type="hidden" id="sale_id" value="<?php echo $this->_user->sale_id; ?>" />
<input type="hidden" id="sale_team" value="<?php echo $this->_user->team_id; ?>" />
<input type="hidden" id="user_id" value="<?php echo $this->_user->id; ?>" />
<input type="hidden" id="vat_rate" value="<?php echo getConfig('SALE_VAT_RATE'); ?>" />
<input type="hidden" id="vat_code" value="<?php echo getConfig('SALE_VAT_CODE');?>" />
<input type="hidden" id="priceList" value="1" />
<input type="hidden" id="saveType" value="0"> <!-- 0 = Normal save, 1 = Draft, 2 = Reserve -->
<input type="hidden" id="creditLimit" value="<?php echo getConfig('CREDIT_LIMIT') == 1 ? 1 : 0; ?>" />
<input type="hidden" id="allow-reserve" value="<?php echo getConfig('ALLOW_RESERVE') == 1 ? 1 : 0; ?>" />
<input type="hidden" id="limit-reserve" value="<?php echo getConfig('LIMIT_RESERVE') == 1 ? 1 : 0; ?>" />

<script>
  $('#projects').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/sales_order/sales_order.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/sales_order/sales_order_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/sales_order/sales_order_import.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>

<?php $this->load->view('include/footer'); ?>