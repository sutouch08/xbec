<?php $this->load->view('include/header'); ?>
<?php $pm = get_permission('SOODSO', $this->_user->uid, $this->_user->id_profile); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.autosize.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sortable.min.js"></script>
<?php $this->load->view('quotation/style'); ?>
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-warning" onclick="goTo('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
		<?php if ($this->pm->can_add) : ?>
			<button type="button" class="btn btn-white btn-primary" onclick="duplicateSQ('<?php echo $order->code; ?>')"><i class="fa fa-copy"></i> Duplicate</button>
		<?php endif; ?>
		<?php if ($pm->can_add && $order->Status == 1 && ($order->Approved == 'A' or $order->Approved == 'S')) : ?>
			<button type="button" class="btn btn-white btn-success" onclick="createSO('<?php echo $order->code; ?>')"><i class="fa fa-copy"></i> Create Sale Order</button>
		<?php endif; ?>
		<?php if ($this->pm->can_edit && $order->Status == -1) : ?>
			<button type="button" class="btn btn-white btn-warning" onclick="edit('<?php echo $order->code; ?>', '<?php echo $pageNo; ?>')"><i class="fa fa-pencil"></i> Edit</button>
		<?php endif; ?>
		<button type="button" class="btn btn-white btn-info" onclick="printSQ()"><i class="fa fa-print"></i> Print</button>
		<?php if (empty($order->DocEntry) && ($order->Status == 3 or $order->Status == 0)) : ?>
			<button type="button" class="btn btn-white btn-success" onclick="sendToSap('<?php echo $order->code; ?>')"><i class="fa fa-send"></i> Send to SAP</button>
		<?php endif; ?>
		<?php if ($this->_SuperAdmin) : ?>
			<button type="button" class="btn btn-white btn-danger" onclick="dumpJson('<?php echo $order->code; ?>')">GET JSON</button>
		<?php endif; ?>
	</div>
</div><!-- End Row -->
<hr class="padding-5" />
<?php
if ($order->Status != 2 && $order->Approved == 'R')
{
	$this->load->view('reject_watermark');
}

if ($order->Status == 2)
{
	$this->load->view('cancle_watermark');
}
?>

<?php $this->load->view('quotation/quotation_view_header'); ?>
<?php $this->load->view('quotation/quotation_view_detail'); ?>
<?php $this->load->view('quotation/quotation_view_footer'); ?>

<input type="hidden" id="order_code" value="<?php echo $order->code; ?>" />
<input type="hidden" id="sale_id" value="<?php echo $order->SlpCode; ?>" />
<input type="hidden" id="vat_rate" value="<?php echo $order->VatRate; ?>" />
<input type="hidden" id="vat_code" value="<?php echo $order->VatGroup; ?>" />
<input type="hidden" id="priceList" value="<?php echo $order->PriceList; ?>" />
<input type="hidden" id="user_id" value="<?php echo $order->user_id; ?>" />
<input type="hidden" id="uname" value="<?php echo $order->uname; ?>" />




<script src="<?php echo base_url(); ?>scripts/quotation/quotation.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_view.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>




<?php $this->load->view('include/footer'); ?>