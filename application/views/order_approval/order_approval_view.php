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
			<button type="button" class="btn btn-white btn-default" onclick="goTo('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>			
		</p>
	</div>
</div><!-- End Row -->
<hr class="padding-5" />
<?php $this->load->view('order_approval/order_approval_view_header'); ?>
<?php $this->load->view('order_approval/order_approval_view_detail'); ?>
<?php $this->load->view('order_approval/order_approval_view_footer'); ?>

<input type="hidden" id="sale_id" value="<?php echo $order->SlpCode; ?>" />
<input type="hidden" id="vat_rate" value="<?php echo $order->VatRate; ?>" />
<input type="hidden" id="vat_code" value="<?php echo $order->VatGroup; ?>" />
<input type="hidden" id="priceList" value="<?php echo $order->PriceList; ?>" />
<input type="hidden" id="user_id" value="<?php echo $order->user_id; ?>" />
<input type="hidden" id="uname" value="<?php echo $order->uname; ?>" />


<script src="<?php echo base_url(); ?>scripts/order_approval/order_approval.js?v=<?php echo date('Ymd'); ?>"></script>


<?php $this->load->view('include/footer'); ?>