<?php $this->load->view('include/header'); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.autosize.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sortable.min.js"></script>
<?php $this->load->view('quotation/style'); ?>

<div class="row">
	<div class="col-sm-6 col-xs-6 padding-5">
		<h3 class="title">
			<?php echo $this->title; ?>
		</h3>
	</div>
	<div class="col-sm-6 col-xs-6 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-default" onclick="leave('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="padding-5" />

<?php $this->load->view('quotation/quotation_edit_header'); ?>
<?php $this->load->view('quotation/quotation_edit_detail'); ?>
<?php $this->load->view('quotation/quotation_edit_footer'); ?>
<?php $this->load->view('quotation/quotation_detail_template'); ?>

<input type="hidden" id="vat_rate" value="<?php echo $order->VatRate; ?>" />
<input type="hidden" id="vat_code" value="<?php echo $order->VatGroup; ?>" />
<input type="hidden" id="priceList" value="<?php echo $order->PriceList; ?>" />
<input type="hidden" id="user_id" value="<?php echo $order->user_id; ?>" />
<input type="hidden" id="uname" value="<?php echo $order->uname; ?>" />
<input type="hidden" id="sale_team" value="<?php echo $order->sale_team; ?>" />
<input type="file" class="hide" name="uploadFile" id="uploadFile" accept=".xlsx" />


<script>
	$('#projects').select2();
</script>

<script src="<?php echo base_url(); ?>scripts/quotation/quotation.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_import.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>




<?php $this->load->view('include/footer'); ?>