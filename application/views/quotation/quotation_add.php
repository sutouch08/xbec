<?php $this->load->view('include/header'); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.autosize.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sortable.min.js"></script>
<?php $this->load->view('quotation/style'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default" onclick="leave()"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
  </div>
</div><!-- End Row -->
<hr />
<?php $this->load->view('quotation/quotation_add_header'); ?>
<?php $this->load->view('quotation/quotation_add_detail'); ?>
<?php $this->load->view('quotation/quotation_add_footer'); ?>
<?php $this->load->view('quotation/quotation_detail_template'); ?>

<input type="hidden" id="sale_id" value="<?php echo $this->_user->sale_id; ?>" />
<input type="hidden" id="sale_team" value="<?php echo $this->_user->team_id; ?>" />
<input type="hidden" id="vat_rate" value="<?php echo getConfig('SALE_VAT_RATE'); ?>" />
<input type="hidden" id="vat_code" value="<?php echo getConfig('SALE_VAT_CODE'); ?>" />
<input type="hidden" id="priceList" value="1" />
<input type="file" class="hide" name="uploadFile" id="uploadFile" accept=".xlsx" />

<script>
  $('#projects').select2();
</script>

<script src="<?php echo base_url(); ?>scripts/quotation/quotation.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_import.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>

<?php $this->load->view('include/footer'); ?>